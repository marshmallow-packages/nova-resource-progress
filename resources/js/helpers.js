module.exports = {
    calculateProgressForegroundColor: function (colors, progress) {
        let selectedColor = null;

        // Convert the object into an array of keys (thresholds) and sort them numerically
        const thresholds = Object.keys(colors).map(Number).sort((a, b) => a - b);

        // Find the closest threshold that is less than or equal to the progress value
        for (let i = 0; i < thresholds.length; i++) {
            if (progress >= thresholds[i]) {
                selectedColor = colors[thresholds[i]];
            } else {
                break;
            }
        }

        return selectedColor;
    }
};
