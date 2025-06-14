window.renderCharts = async function (chartConfigs) {
  await import('moment');
  const ChartModule = await import('chart.js');
  const Chart = ChartModule.default;

  window.renderedCharts = window.renderedCharts || {};

  chartConfigs.forEach((chart) => {
    const canvas = document.getElementById(chart.id);
    if (chart.show && canvas) {
      const ctx = canvas.getContext('2d');

      if (window.renderedCharts[chart.id]) {
        window.renderedCharts[chart.id].destroy();
      }

      window.renderedCharts[chart.id] = new Chart(ctx, chart.config);
    }
  });
};
