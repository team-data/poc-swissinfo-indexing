function bubbleChart() {
    let detailsContainer = "";
    let linkBaseUrl = "";
    let format = d3.format(".3f");
    var width = 960,
        height = 960,
        maxRadius = 6,
        columnForColors = "score",
        columnForRadius = "value";

    function chart(selection) {
        const data = selection.datum();
        const svg = selection.selectAll('svg');
        svg.attr('width', width).attr('height', height);

        // var tooltip = selection
        //     .append("div")
        //     .style("position", "absolute")
        //     .style("visibility", "hidden")
        //     .style("color", "white")
        //     .style("padding", "8px")
        //     .style("background-color", "#626D71")
        //     .style("border-radius", "6px")
        //     .style("text-align", "center")
        //     .style("font-family", "monospace")
        //     .style("width", "400px")
        //     .text("");

        const details = d3.select(detailsContainer);
        let simulation = d3.forceSimulation(data)
            .force("charge", d3.forceManyBody().strength([-100]))
            .force("x", d3.forceX())
            .force("y", d3.forceY())
            .on("tick", ticked);

        function ticked(e) {
            node
                .attr("cx", function(d) { return d.x; })
                .attr("cy", function(d) { return d.y; })
            ;
        }

        let colorCircles = d3.scaleOrdinal(d3.schemeCategory10);
        let scaleRadius = d3.scaleLinear().domain([
            d3.min(data, function(d) { return +d[columnForRadius]; }),
            d3.max(data, function(d) { return +d[columnForRadius]; })
        ]).range([6, 30]);

        let node = svg.selectAll("circle")
            .data(data)
            .enter()
            .append("circle")
            .attr('r', function(d) {
                return scaleRadius(d[columnForRadius])
            })
            .style("fill", function(d) {
                return colorCircles(d[columnForColors])
            })
            .attr('transform', 'translate(' + [width / 2, height / 2] + ')')
            //.on("mouseover", function(data) {
            //    tooltip.html(getTooltip(data));
            //    return tooltip.style("visibility", "visible");
            //})
            //.on("mousemove", function() {
            //    return tooltip
            //        .style("top", (d3.event.pageY - 10) + "px")
            //        .style("left", (d3.event.pageX + 10) + "px")
            //    ;
            //})
            //.on("mouseout", function() {
            //    return tooltip.style("visibility", "hidden");
            //})
            .on("click", function(data) {
                details.html(getDetails(data));
                return details.style("visibility", "visible");
            })
        ;
    }

    function getTooltip(d) {
        return d.title;
    }

    function getDetails(d) {

        return `<h4>${d.label}</h4>` +
            "<span style='font-size: .8em;'>Size: " + d[columnForRadius] + " - " +
            "Cluster Score: " + format(d[columnForColors])+ "</span>" +
            "<ul>" + d.ids.map(x => "<li><a href='"+linkBaseUrl+x+"'>"+x+"</a></li>").join('') + "</ul>";
    }

    chart.width = function(value) {
        if (!arguments.length) {
            return width;
        }
        width = value;
        return chart;
    };

    chart.height = function(value) {
        if (!arguments.length) {
            return height;
        }
        height = value;
        return chart;
    };

    chart.detailsContainer = function(value) {
        detailsContainer = value;
        return chart;
    };

    chart.columnForColors = function(value) {
        if (!arguments.columnForColors) {
            return columnForColors;
        }
        columnForColors = value;
        return chart;
    };

    chart.columnForRadius = function(value) {
        if (!arguments.columnForRadius) {
            return columnForRadius;
        }
        columnForRadius = value;
        return chart;
    };

    chart.linkBaseUrl = function(value) {
        linkBaseUrl = value;
        return chart;
    };

    return chart;
}