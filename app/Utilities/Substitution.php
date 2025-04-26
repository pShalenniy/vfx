<?php

declare(strict_types=1);

namespace App\Utilities;

use function array_keys;
use function data_get;
use function implode;
use function mb_strlen;
use function mb_substr;
use function preg_match;
use function preg_replace_callback;
use function str_contains;

use const PREG_OFFSET_CAPTURE;

class Substitution
{
    protected array $substitutions = [];

    public function getSubstitutions(): array
    {
        return $this->substitutions;
    }

    public function addSubstitution(string $key, $value): void
    {
        $this->substitutions[$key] = $value;
    }

    public function setSubstitutions(array $substitutions): void
    {
        $this->substitutions = $substitutions;
    }

    public function substitute(string $message): string
    {
        $keys = implode('|', array_keys($this->substitutions));

        if (!empty($this->substitutions['loops'])) {
            $message = $this->loopSubstitutions($this->substitutions['loops'], $message);
        }

        return $this->replaceSubstitutionData($keys, $message, $this->substitutions);
    }

    protected function compileSubstitutionsPattern(string $keys): string
    {
        return "/\[(?<key>{$keys})(?:\:(?<attribute>[^\]]+))?\]/";
    }

    protected function getClosableMatch(string $message, string $key): array
    {
        preg_match(
            "~(?<start>\[{$key}])(?<content>.*)(?<end>\[/{$key}])~",
            $message,
            $match,
            PREG_OFFSET_CAPTURE,
        );

        return $match;
    }

    protected function getContentFromClosableMatch(string $message, string $key): string
    {
        $match = $this->getClosableMatch($message, $key);

        return mb_substr($message, $match['content'][1], $match['end'][1] - $match['content'][1]);
    }

    protected function splitMessageToClosableMatch(string $message, string $key): array
    {
        $match = $this->getClosableMatch($message, $key);

        if (empty($match)) {
            return $match;
        }

        $leftPart = mb_substr($message, 0, $match['start'][1]);

        $rightPart = mb_substr(
            $message,
            $match['end'][1] + mb_strlen("[/{$key}]"),
        );

        $contentRaw = mb_substr(
            $message,
            $match['start'][1],
            $match['end'][1] - $match['start'][1] + mb_strlen($match['end'][0]),
        );

        return [
            'leftPart' => $leftPart,
            'rightPart' => $rightPart,
            'contentRaw' => $contentRaw,
        ];
    }

    protected function loopSubstitutions($substitutions, $message): string
    {
        foreach ($substitutions as $loopKey => $loopData) {
            $key = "loop:{$loopKey}";

            if (str_contains($message, "[{$key}]")) {
                $messageContent = $this->splitMessageToClosableMatch($message, $key);

                if (empty($messageContent)) {
                    return $message;
                }

                $content = $this->processSubstitutionData(
                    $loopData,
                    $this->getContentFromClosableMatch($messageContent['contentRaw'], $key),
                );

                $message = "{$messageContent['leftPart']}{$content}{$messageContent['rightPart']}";
            }
        }

        return $message;
    }

    protected function processSubstitutionData(
        array $substitution,
        string $message,
    ): string {
        $substitutionData = [];

        $separator = $substitution['separator'] ?? "\n";

        $loopData = $substitution['data'] ?? $substitution;

        foreach ($loopData as $value) {
            $substitutionMessage = $message;

            $keys = implode('|', array_keys($value));

            if (!empty($value['loops']) && str_contains($message, 'loop')) {
                $substitutionMessage = $this->processSubstitution($message, $value['loops']);
            }

            $substitutionMessage = $this->replaceSubstitutionData($keys, $substitutionMessage, $value);

            $substitutionData[] = $substitutionMessage;
        }

        return implode($separator, $substitutionData);
    }

    protected function processSubstitution(string $message, array $value): string
    {
        foreach ($value as $keyItem => $valueItem) {
            $key = "loop:{$keyItem}";

            if (!empty($valueItem['loops']) && str_contains($message, $key)) {
                $messageContent = $this->splitMessageToClosableMatch($message, $key);

                if (empty($messageContent)) {
                    return $message;
                }

                $content = $this->processSubstitution($messageContent['contentRaw'], $valueItem);
            } else {
                $key = "loop:{$keyItem}";

                $messageContent = $this->splitMessageToClosableMatch($message, $key);

                if (empty($messageContent)) {
                    return $message;
                }

                $content = $this->replaceSubstitutionData(
                    implode('|', array_keys($valueItem)),
                    $this->getContentFromClosableMatch($messageContent['contentRaw'], $key),
                    $valueItem,
                );
            }

            $message = "{$messageContent['leftPart']}{$content}{$messageContent['rightPart']}";
        }

        return $message;
    }

    protected function replaceSubstitutionData(
        string $keys,
        string $message,
        array $data,
    ): string {
        return (string) preg_replace_callback(
            $this->compileSubstitutionsPattern($keys),
            static function (array $matches) use ($data) {
                return !isset($matches['attribute'])
                    ? ($data[$matches['key']] ?? '')
                    : data_get($data[$matches['key']] ?? [], $matches['attribute'], '');
            },
            $message,
        );
    }
}
