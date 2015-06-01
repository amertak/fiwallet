var React = require('react');
var DashboardActions = require('../actions/DashboardActions');
var _ = require('lodash');
var Filter = require('./Filter.react');

var Filters = React.createClass({
  propTypes: {
    filters: React.PropTypes.array,
    activeFilters: React.PropTypes.array
  },
  render: function () {
    var that = this;
    if (!this.props.filters) {
      return null;
    }

    var components = this.props.filters.map(function (filter) {
      var isActive = false;
      if (_.find(that.props.activeFilters, function (activeFilter) {
          return activeFilter === filter.id;
        })) {
        isActive = true;
      }

      return (<Filter filter={filter} isActive={isActive} key={filter.id} />)
    });

    return (<ul className="nav nav-pills">{components}</ul>);
  }
});

module.exports = Filters;
