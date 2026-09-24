import ChartDataLabels from 'chartjs-plugin-datalabels';

window.filamentChartJsPlugins ??= [];
window.filamentChartJsPlugins.push(ChartDataLabels);

const tooltipNote = {
    id: 'tooltipNote',
    beforeDraw: chart => {
      console.log('chart');
    }
  }

  const doughnutLabel = {
    id: 'doughnutLabel',
    beforeDatasetsDraw(chart) {
        const type = chart.config.type;
        if (type !== 'doughnut' && type !== 'pie') {
            return;
        }
        const plugins = chart.config.options?.plugins;

        // Backward compatibility:
        // - plugins.doughnutLabel.label (string)
        // - dataset.centerLabel (string)
        // New (used by QuestionChartAnswersChartWidget):
        // - plugins.doughnutlabel.labels: Array<{ text: string, font?: { size?: number, weight?: string|number, family?: string } }>
        const optsCamel = plugins?.doughnutLabel;
        const optsLower = plugins?.doughnutlabel;
        const opts = optsCamel ?? optsLower;

        const labelsFromOpts = opts?.labels;
        if (Array.isArray(labelsFromOpts) && labelsFromOpts.length > 0) {
            const meta = chart.getDatasetMeta(0);
            if (!meta?.data?.length) {
                return;
            }

            const xCoor = meta.data[0].x;
            const yCoor = meta.data[0].y;
            const ctx = chart.ctx;
            ctx.save();
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillStyle = 'rgba(0, 0, 0, 1)';

            const defaultLineHeight = 22;
            const lineHeight = typeof opts?.lineHeight === 'number' ? opts.lineHeight : defaultLineHeight;
            const totalHeight = labelsFromOpts.length * lineHeight;
            const startY = yCoor - (totalHeight / 2) + (lineHeight / 2);

            labelsFromOpts.forEach((labelObj, i) => {
                const text = typeof labelObj?.text === 'string' ? labelObj.text : '';
                if (text === '') {
                    return;
                }

                const size = Number(labelObj?.font?.size) || 16;
                const weight = labelObj?.font?.weight ?? 'bold';
                const family = labelObj?.font?.family ?? 'sans-serif';
                ctx.font = `${weight} ${size}px ${family}`;
                ctx.fillText(text, xCoor, startY + i * lineHeight);
            });

            ctx.restore();
            return;
        }

        const labelFromOpts = opts?.label;
        const labelFromDataset = chart.data?.datasets?.[0]?.centerLabel;
        const text = (typeof labelFromOpts === 'string' && labelFromOpts !== '')
            ? labelFromOpts
            : (typeof labelFromDataset === 'string' ? labelFromDataset : '');
        if (text === '') {
            return;
        }
        const meta = chart.getDatasetMeta(0);
        if (!meta?.data?.length) {
            return;
        }
        const xCoor = meta.data[0].x;
        const yCoor = meta.data[0].y;
        const ctx = chart.ctx;
        ctx.save();
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillStyle = 'rgba(0, 0, 0, 1)';
        const lines = text.split('\n');
        const lineHeight = 22;
        const totalHeight = lines.length * lineHeight;
        const startY = yCoor - (totalHeight / 2) + (lineHeight / 2);
        const fontSize = lines.length > 1 ? 14 : 18;
        ctx.font = `bold ${fontSize}px sans-serif`;
        lines.forEach((line, i) => {
            ctx.fillText(line.trim(), xCoor, startY + i * lineHeight);
        });
        
        ctx.restore();
    }
  };

  // const percYLabel={
  //   id: 'percYLabel',
  //   ///*
  //   beforeDatasetsDraw(chart){
  //       if(chart.config.options.scales.y.ticks.callback.label== undefined){
  //           return;
  //       }
  //       const{ctx,data}=chart;
  //       ctx.save();
  //       var text=chart.config.optionsscales.y.ticks.callback.label + '%';
        
  //       ctx.fillText(text);
  //   }
  // }
 
window.filamentChartJsPlugins ??= []
//window.filamentChartJsPlugins.push(tooltipNote);
window.filamentChartJsPlugins.push(doughnutLabel);
