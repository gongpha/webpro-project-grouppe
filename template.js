const currMil = (new Date()).valueOf();
const dateMil = 86400000;

function applyChart(list_item, use_baht) {
	if (list_item.data.length === 0) {
		let ctx = document.getElementById(list_item.canvasID);
		ctx.parentNode.innerHTML = "<h3 class='text-center'>ไม่มีข้อมูล</h3>";
		return;
	}

	const labels = [];
	const data = [];

	for (let j = 0; j < Object.keys(list_item.data).length; j++) {
		labels.push(new Date(currMil - (dateMil * (Object.keys(list_item.data).length - j - 1))));
		data.push(list_item.data[Object.keys(list_item.data)[j].toString()]);
	}

	const realdata = {
		labels: labels,
		datasets: [
			{
				data: data,
			},
		],
	};

	let ctx = document.getElementById(list_item.canvasID).getContext("2d");

	let chart = new Chart(ctx, {
		type: "line",
		data: realdata,

		options: {
			plugins: {
				legend: {
					display: false
				},
			},
			scales: {
				x: {
					type: "time",
				},
				y: {
					ticks: {
						stepSize: 1,
						callback: function(value, index, values) {
							if (use_baht) {
								return "฿" + value;
							} else {
								return value;
							}
						}
					}
				}
			},
		},
	});
}