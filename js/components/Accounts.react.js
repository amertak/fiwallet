var React = require('react');
var Router = require('react-router');
var Link = Router.Link;

var DashboardActions = require('../actions/DashboardActions');
var classset = require('react-classset');

var Accounts = React.createClass({
  propTypes: {
    accounts: React.PropTypes.array.isRequired
  },
  handleChange: function () {
    DashboardActions.selectAccount(React.findDOMNode(this.refs.account).value);
  },
  render: function () {

    var options = this.props.accounts.map(function (acc) {
      return <option key={acc.id} value={acc.id}>{acc.name}</option>;
    });

    var style = {maxWidth: '385px'};

    return <div>
      <div className="col-xs-12" style={style}>
        <div className="form-group">
          <label className="control-label" htmlFor="account">Account</label>
          <select className="form-control" onChange={this.handleChange} ref="account" id="account">{options}</select>
        </div>
      </div>
    </div>;
  }

});

module.exports = Accounts;
