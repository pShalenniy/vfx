const engine = require('store/src/store-engine');
const storages = [
  require('store/storages/cookieStorage'),
];

const storage = engine.createStore(storages, []);

export default storage;
