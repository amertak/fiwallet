/*
 * Copyright (c) 2015, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in the root directory of this source tree. An additional grant
 * of patent rights can be found in the PATENTS file in the same directory.
 *
 * TodoConstants
 */

var keyMirror = require('keymirror');

module.exports = keyMirror({
  LOAD: null,
  SAVE: null,
  CREATE: null,
  NEWTRANSACTION_AMOUNT: null,
  NEWTRANSACTION_NAME: null,
  NEWTRANSACTION_DATE: null,
  NEWTRANSACTION_CANCEL: null,
  SELECT_ACCOUNT: null,
  TRANSACTION_NAME: null,
  TRANSACTION_AMOUNT: null,
  TRANSACTION_DATE: null,
  TRANSACTION_NOTES: null,
  TRANSACTION_TAGS: null,
  TRANSACTION_DELETE: null,
  TRANSACTION_UPDATE: null,
  TRANSACTION_CONFIRM: null,
  TRANSACTION_LOAD: null,
  FILTER_ENABLE: null,
  FILTER_DISABLE: null
});
