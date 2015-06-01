var React = require('react');
var DashboardActions = require('../actions/DashboardActions');
var _ = require('lodash');
var classset = require('react-classset');

var Filter = React.createClass({
  propTypes: {
    filter: React.PropTypes.object,
    isActive: React.PropTypes.bool
  },
  handleClick: function () {
    if (!this.props.isActive) {
      DashboardActions.enableFilter(this.props.filter.id);
    } else {
      DashboardActions.disableFilter(this.props.filter.id);
    }
  },
  render: function () {
    if (!this.props.filter) {
      return null;
    }

    var classes = classset({
      'active': this.props.isActive
    });

    return (<li role="presentation" className={classes}><a href="#" onClick={this.handleClick}>{this.props.filter.name}</a></li>);
  }
});

module.exports = Filter;
