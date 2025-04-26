export default {
  methods: {
    setDataSilently(key, callback = () => {}) {
      const watcherCallbacks = {};
      const watcherSyncs = {};

      (this._scope.effects ?? []).forEach((watcher, index) => {
        if (
          (process.env.NODE_ENV === 'production' && watcher.cb.name !== key) ||
          (process.env.NODE_ENV !== 'production' && watcher.expression !== key)
        ) {
          return;
        }

        watcherCallbacks[index] = watcher.cb;
        watcherSyncs[index] = watcher.sync;

        this._scope.effects[index].cb = () => null;
        this._scope.effects[index].sync = true;
      });

      callback();

      Object.keys(watcherCallbacks).forEach((key) => {
        this._scope.effects[key].cb = watcherCallbacks[key];
        this._scope.effects[key].sync = watcherSyncs[key];
      });
    },
  },
};
