var React = require('react');
var classset = require('react-classset');

var Tag = React.createClass({
    propTypes: {
        tag: React.PropTypes.string.isRequired
    },
    render: function () {
        var classes = classset({
            'label': true,
            'label-info': true
        });

        return (<span className={classes}>{this.props.tag}</span>)
    }
});

module.exports = Tag;
