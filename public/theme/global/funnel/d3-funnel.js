(function(global) {

	/* global d3 */
	/* jshint bitwise: false */
	"use strict";

	/**
	 * D3Funnel
	 *
	 * An object representing a D3-driven funnel chart.
	 *
	 * @param {string} selector A selector for element to contain the funnel.
	 */
	function D3Funnel(selector) {
		this.selector = selector;

		// Default configuration values
		this.defaults = {
			width: 100,
			height: 200,
			bottomWidth: 1/3,
			bottomPinch: 0,
			isCurved: false,
			curveHeight: 20,
			fillType: "solid",
			isInverted: false,
			hoverEffects: false,
			dynamicArea: false,
			label: {
				fontSize: "14px"
			}
		};
	}

	/**
	 * Check if the supplied value is an array.
	 *
	 * @param {mixed} value
	 *
	 * @return {bool}
	 */
	D3Funnel.prototype._isArray = function(value) {
		return Object.prototype.toString.call(value) === "[object Array]";
	};

	/**
	 * Extends an object with the members of another.
	 *
	 * @param {Object} a The object to be extended.
	 * @param {Object} b The object to clone from.
	 *
	 * @return {Object}
	 */
	D3Funnel.prototype._extend = function(a, b) {
		var prop;
		for (prop in b) {
			if (b.hasOwnProperty(prop)) {
				a[prop] = b[prop];
			}
		}
		return a;
	};

	/**
	 * Draw onto the container with the data and configuration specified. This
	 * will clear any previous SVG element in the container and draw a new
	 * funnel chart on top of it.
	 *
	 * @param {array}  data    A list of rows containing a category, a count,
	 *                         and optionally a color (in hex).
	 * @param {Object} options An optional configuration object for chart
	 *                         options.
	 *
	 * @param {int}    options.width        Initial width. Specified in pixels.
	 * @param {int}    options.height       Chart height. Specified in pixels.
	 * @param {int}    options.bottomWidth  Specifies the width percent the
	 *                                      bottom should be in relation to the
	 *                                      chart's overall width.
	 * @param {int}    options.bottomPinch  How many sections (from the bottom)
	 *                                      should be "pinched" to have fixed
	 *                                      width defined by options.bottomWidth.
	 * @param {bool}   options.isCurved     Whether or not the funnel is curved.
	 * @param {int}    options.curveHeight  The height of the curves. Only
	 *                                      functional if isCurve is set.
	 * @param {string} options.fillType     The section background type. Either
	 *                                      "solid" or "gradient".
	 * @param {bool}   options.isInverted   Whether or not the funnel should be
	 *                                      inverted to a pyramid.
	 * @param {bool}   options.hoverEffects Whether or not the funnel hover
	 *                                      effects should be shown.
	 * @param {bool}   options.dynamicArea  Whether or not the area should be
	 *                                      dynamically calculated based on
	 *                                      data counts.
	 * @param {Object} options.label
	 * @param {Object} options.label.fontSize
	 */
	D3Funnel.prototype.draw = function(data, options) {
		// Initialize chart options
		this._initialize(data, options);

		// Remove any previous drawings
		d3.select(this.selector).selectAll("svg").remove();

		// Add the SVG and group element
		var svg = d3.select(this.selector)
			.append("svg")
			.attr("width", this.width)
			.attr("height", this.height)
			.append("g");
		var group = {};
		var path  = {};

		var sectionPaths = this._makePaths();

		// Define color gradients
		if (this.fillType === "gradient") {
			this._defineColorGradients(svg);
		}

		// Add top oval if curved
		if (this.isCurved) {
			this._drawTopOval(svg, sectionPaths);
		}

		// Add each block section
		for (var i = 0; i < sectionPaths.length; i++) {
			// Set the background color
			var fill = this.fillType !== "gradient" ?
				this.data[i][2] :
				"url(#gradient-" + i + ")";

			// Prepare data to assign to the section
			data = {
				index: i,
				label: this.data[i][0],
				value: this.data[i][1],
				baseColor: this.data[i][2],
				fill: fill
			};

			// Construct path string
			var paths = sectionPaths[i];
			var pathStr = "";

			// Iterate through each point
			for (var j = 0; j < paths.length; j++) {
				path = paths[j];
				pathStr += path[2] + path[0] + "," + path[1] + " ";
			}

			// Create a group just for this block
			group = svg.append("g");

			// Draw the sections's path and append the data
			path = group.append("path")
				.attr("fill", fill)
				.attr("d", pathStr)
				.data([data]);

			// Add the hover events
			if (this.hoverEffects) {
				path.on("mouseover", this._onMouseOver)
					.on("mouseout", this._onMouseOut);
			}

			// Add the section label
			var textStr = this.data[i][0] + ": " + this.data[i][1].toLocaleString();
			var textX   = this.width / 2;   // Center the text
			var textY   = !this.isCurved ?  // Average height of bases
				(paths[1][1] + paths[2][1]) / 2 :
				(paths[2][1] + paths[3][1]) / 2 + (this.curveHeight / this.data.length);

			group.append("text")
				.text(textStr)
				.attr({
					"x": textX,
					"y": textY,
					"text-anchor": "middle",
					"dominant-baseline": "middle",
					"fill": "#fff",
					"pointer-events": "none"
				})
				.style("font-size", this.label.fontSize);
		}
	};

	/**
	 * Initialize and calculate important variables for drawing the chart.
	 *
	 * @param {array}  data
	 * @param {Object} options
	 */
	D3Funnel.prototype._initialize = function(data, options) {
		if (!this._isArray(data) || data.length === 0 ||
			!this._isArray(data[0]) || data[0].length < 2) {
			throw {
				name: "D3 Funnel Data Error",
				message: "Funnel data is not valid."
			};
		}

		// Initialize options if not set
		options = typeof options !== "undefined" ? options : {};

		this.data = data;

		// Counter
		var i = 0;

		// Prepare the configuration settings based on the defaults
		// Set the default width and height based on the container
		var settings = this._extend({}, this.defaults);
		settings.width  = parseInt(d3.select(this.selector).style("width"), 10);
		settings.height = parseInt(d3.select(this.selector).style("height"), 10);

		// Overwrite default settings with user options
		var keys = Object.keys(options);
		for (i = 0; i < keys.length; i++) {
			if (keys[i] !== "label") {
				settings[keys[i]] = options[keys[i]];
			}
		}

		// Label settings
		if ("label" in options) {
			if ("fontSize" in options.label) {
				settings.label.fontSize = options.label.fontSize;
			}
		}
		this.label = settings.label;

		// In the case that the width or height is not valid, set
		// the width/height as its default hard-coded value
		if (settings.width <= 0) {
			settings.width = this.defaults.width;
		}
		if (settings.height <= 0) {
			settings.height = this.defaults.height;
		}

		// Initialize the colors for each block section
		var colorScale = d3.scale.category10();
		for (i = 0; i < this.data.length; i++) {
			var hexExpression = /^#([0-9a-f]{3}|[0-9a-f]{6})$/i;

			// If a color is not set for the record, add one
			if (typeof this.data[i][2] === "undefined" ||
				!hexExpression.test(this.data[i][2])) {
				this.data[i][2] = colorScale(i);
			}
		}

		// Initialize funnel chart settings
		this.width = settings.width;
		this.height = settings.height;
		this.bottomWidth = settings.width * settings.bottomWidth;
		this.bottomPinch = settings.bottomPinch;
		this.isCurved = settings.isCurved;
		this.curveHeight = settings.curveHeight;
		this.fillType = settings.fillType;
		this.isInverted = settings.isInverted;
		this.hoverEffects = settings.hoverEffects;
		this.dynamicArea = settings.dynamicArea;

		// Calculate the bottom left x position
		this.bottomLeftX = (this.width - this.bottomWidth) / 2;

		// Change in x direction
		// Will be sharper if there is a pinch
		this.dx = this.bottomPinch > 0 ?
			this.bottomLeftX / (data.length - this.bottomPinch) :
			this.bottomLeftX / data.length;
		// Change in y direction
		// Curved chart needs reserved pixels to account for curvature
		this.dy = this.isCurved ?
			(this.height - this.curveHeight) / data.length :
			this.height / data.length;
	};

	/**
	 * Create the paths to be used to define the discrete funnel sections and
	 * returns the results in an array.
	 *
	 * @return {array}
	 */
	D3Funnel.prototype._makePaths = function() {
		var paths = [];

		// Initialize velocity
		var dx = this.dx;
		var dy = this.dy;

		// Initialize starting positions
		var prevLeftX = 0;
		var prevRightX = this.width;
		var prevHeight = 0;

		// Start from the bottom for inverted
		if (this.isInverted) {
			prevLeftX = this.bottomLeftX;
			prevRightX = this.width - this.bottomLeftX;
		}

		// Initialize next positions
		var nextLeftX = 0;
		var nextRightX = 0;
		var nextHeight = 0;

		var middle = this.width / 2;

		// Move down if there is an initial curve
		if (this.isCurved) {
			prevHeight = 10;
		}

		var topBase = this.width;
		var bottomBase = 0;

		var totalArea = this.height * (this.width + this.bottomWidth) / 2;
		var slope = 2 * this.height / (this.width - this.bottomWidth);

		var totalCount = 0;
		var count = 0;

		// Harvest total count
		for (var i = 0; i < this.data.length; i++) {
			totalCount += this.data[i][1];
		}

		// Create the path definition for each funnel section
		// Remember to loop back to the beginning point for a closed path
		for (i = 0; i < this.data.length; i++) {
			count = this.data[i][1];

			// Calculate dynamic shapes based on area
			if (this.dynamicArea) {
				var ratio = count / totalCount;
				var area  = ratio * totalArea;

				bottomBase = Math.sqrt((slope * topBase * topBase - (4 * area)) / slope);
				dx = (topBase / 2) - (bottomBase / 2);
				dy = (area * 2) / (topBase + bottomBase);

				if (this.isCurved) {
					dy = dy - (this.curveHeight/this.data.length);
				}

				topBase = bottomBase;
			}

			// Stop velocity for pinched sections
			if (this.bottomPinch > 0) {

				// Check if we've reached the bottom of the pinch
				// If so, stop changing on x
				if (!this.isInverted) {
					if (i >= this.data.length - this.bottomPinch) {
						dx = 0;
					}
				}
				// Pinch at the first sections relating to the bottom pinch
				// Revert back to normal velocity after pinch
				else {
					// Revert velocity back to the intial if we are using
					// static area's (prevents zero velocity if isInverted
					// and bottomPinch are non trivial and dynamicArea is false)
					if (!this.dynamicArea) {
						dx = this.dx;
					}

					dx = i < this.bottomPinch ? 0 : dx;
				}
			}

			// Calculate the position of next section
			nextLeftX = prevLeftX + dx;
			nextRightX = prevRightX - dx;
			nextHeight = prevHeight + dy;

			// Expand outward if inverted
			if (this.isInverted) {
				nextLeftX = prevLeftX - dx;
				nextRightX = prevRightX + dx;
			}

			// Plot curved lines
			if (this.isCurved) {
				paths.push([
					// Top Bezier curve
					[prevLeftX, prevHeight, "M"],
					[middle, prevHeight + (this.curveHeight - 10), "Q"],
					[prevRightX, prevHeight, ""],
					// Right line
					[nextRightX, nextHeight, "L"],
					// Bottom Bezier curve
					[nextRightX, nextHeight, "M"],
					[middle, nextHeight + this.curveHeight, "Q"],
					[nextLeftX, nextHeight, ""],
					// Left line
					[prevLeftX, prevHeight, "L"]
				]);
			}
			// Plot straight lines
			else {
				paths.push([
					// Start position
					[prevLeftX, prevHeight, "M"],
					// Move to right
					[prevRightX, prevHeight, "L"],
					// Move down
					[nextRightX, nextHeight, "L"],
					// Move to left
					[nextLeftX, nextHeight, "L"],
					// Wrap back to top
					[prevLeftX, prevHeight, "L"],
				]);
			}

			// Set the next section's previous position
			prevLeftX = nextLeftX;
			prevRightX = nextRightX;
			prevHeight = nextHeight;
		}

		return paths;
	};

	/**
	 * Define the linear color gradients.
	 *
	 * @param {Object} svg
	 */
	D3Funnel.prototype._defineColorGradients = function(svg) {
		var defs = svg.append("defs");

		// Create a gradient for each section
		for (var i = 0; i < this.data.length; i++) {
			var color = this.data[i][2];
			var shade = shadeColor(color, -0.25);

			// Create linear gradient
			var gradient = defs.append("linearGradient")
				.attr({
					id: "gradient-" + i
				});

			// Define the gradient stops
			var stops = [
				[0, shade],
				[40, color],
				[60, color],
				[100, shade]
			];

			// Add the gradient stops
			for (var j = 0; j < stops.length; j++) {
				var stop = stops[j];
				gradient.append("stop").attr({
					offset: stop[0] + "%",
					style:  "stop-color:" + stop[1]
				});
			}
		}
	};

	/**
	 * Draw the top oval of a curved funnel.
	 *
	 * @param {Object} svg
	 * @param {array}  sectionPaths
	 */
	D3Funnel.prototype._drawTopOval = function(svg, sectionPaths) {
		var leftX = 0;
		var rightX = this.width;
		var centerX = this.width / 2;

		if (this.isInverted) {
			leftX = this.bottomLeftX;
			rightX = this.width - this.bottomLeftX;
		}

		// Create path form top-most section
		var paths = sectionPaths[0];
		var path = "M" + leftX + "," + paths[0][1] +
			" Q" + centerX + "," + (paths[1][1] + this.curveHeight - 10) +
			" " + rightX + "," + paths[2][1] +
			" M" + rightX + ",10" +
			" Q" + centerX + ",0" +
			" " + leftX + ",10";

		// Draw top oval
		svg.append("path")
			.attr("fill", shadeColor(this.data[0][2], -0.4))
			.attr("d", path);
	};

	/**
	 * @param {Object} data
	 */
	D3Funnel.prototype._onMouseOver = function(data) {
		d3.select(this).attr("fill", shadeColor(data.baseColor, -0.2));
	};

	/**
	 * @param {Object} data
	 */
	D3Funnel.prototype._onMouseOut = function(data) {
		d3.select(this).attr("fill", data.fill);
	};

	/**
	 * Shade a color to the given percentage.
	 *
 	 * @param {string} color A hex color.
 	 * @param {float}  shade The shade adjustment. Can be positive or negative.
	 */
	function shadeColor(color, shade) {
		var f = parseInt(color.slice(1), 16);
		var t = shade < 0 ? 0 : 255;
		var p = shade < 0 ? shade * -1 : shade;
		var R = f >> 16, G = f >> 8 & 0x00FF;
		var B = f & 0x0000FF;

		var converted = (0x1000000 + (Math.round((t - R) * p) + R) *
			0x10000 + (Math.round((t - G) * p) + G) *
			0x100 + (Math.round((t - B) * p) + B));

		return "#" + converted.toString(16).slice(1);
	}

	global.D3Funnel = D3Funnel;

})(this);
