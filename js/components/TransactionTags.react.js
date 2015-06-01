var React = require('react');
var Tag = require('./Tag.react');

var TransactionTags = React.createClass({
    propTypes: {
        tags: React.PropTypes.array.isRequired
    },
    render: function () {
       var tags = this.props.tags;

        if (tags.length === 0) {
          return (<div className="panel-footer">
            <span className="label label-warning">No Tag</span>
          </div>);
        }

        var components = tags.map(function (tag) {
            return (<Tag tag={tag} key={tag} />)
        });

        return (<div className="panel-footer">{components}</div>)
    }
});

module.exports = TransactionTags;
