var React = require('react');

var Router = require('react-router');
var RouteHandler = Router.RouteHandler;

var DashboardActions = require('../actions/DashboardActions');

var App = React.createClass({
  componentWillMount: function () {
    DashboardActions.loadData();
  },
  render: function () {
    return (
      <div>
        <RouteHandler/>
      </div>
    );
  }
});

module.exports = App;


