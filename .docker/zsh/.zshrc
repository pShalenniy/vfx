export ZSH="$HOME/.oh-my-zsh"

ZSH_THEME="geoffgarside"
DISABLE_UPDATE_PROMPT=true
DISABLE_AUTO_UPDATE=true

plugins=(command-time zsh-autosuggestions zsh-completions zsh-syntax-highlighting)

source $ZSH/oh-my-zsh.sh

export LANG=en_US.UTF-8
export EDITOR='vim'
export PS1="🐳 [%*] %{$fg[yellow]%}%n@%m%{$reset_color%}:%{$fg[cyan]%}%c%{$reset_color%}$(git_prompt_info) %(!.#.$) "
