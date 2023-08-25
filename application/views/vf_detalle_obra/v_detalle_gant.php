<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<title>Resource level calendars</title>
	<script src="<?php echo base_url();?>public/bower_components/gant/codebase/dhtmlxgantt.js?v=5.2.0"></script>
    <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/gant/codebase/dhtmlxgantt.css?v=5.2.0">

	<style>
		
html, body {
			height: 100%;
			padding: 0px;
			margin: 0px;
		}
		.gantt_task_cell.week_end {
			background-color: #e8e8e8;
		}

		.gantt_task_row.gantt_selected .gantt_task_cell.week_end {
			background-color: #e0e0dd !important;
		}

	</style>
</head>

<body>
<div style="text-align: left;height: 40px;line-height: 40px;">
	<button style="height: 34px;line-height: 30px;margin:3px auto" onclick="toggleMode(this)">Ajustar Gant</button>
	<button style="height: 34px;line-height: 30px;margin:3px auto" onclick="exportGantt(&quot;pdf&quot;)">PDF</button>
</div>
<div id="gantt_here" style='width:100%; height:100%; position: relative;'></div>
<script src="<?php echo base_url();?>public/bower_components/jquery/dist/jquery.min.js"></script>	
<script src="https://export.dhtmlx.com/gantt/api.js"></script>

<script>

    gantt.locale.labels.section_priority = "Color Fondo";
    gantt.locale.labels.section_textColor = "Color de Texto";
    gantt.locale.labels.section_time = "Fecha Inicio y Duracion";
   
    var colors = [
    	{key: "", label: "Default"},
    	{key: "#4B0082", label: "Indigo"},
    	{key: "#FFFFF0", label: "Marfil"},
    	{key: "#F0E68C", label: "Caqui"},
    	{key: "#B0C4DE", label: "LightSteelBlue"},
    	{key: "#32CD32", label: "Verde Lima"},
    	{key: "#7B68EE", label: "MediumSlateBlue"},
    	{key: "#FFA500", label: "Naranja"},
    	{key: "#FF4500", label: "Naranja Rojo"}
    ];

	gantt.serverList("avanceOptions", [
		{key: '0', label: ""},
		{key: '10', label: "10%"},
		{key: '20', label: "20%"},
		{key: '30', label: "30%"},
		{key: '40', label: "40%"},
		{key: '50', label: "50%"},
		{key: '60', label: "60%"},
		{key: '70', label: "70%"},
		{key: '80', label: "80%"},
		{key: '90', label: "90%"},
		{key: '100', label: "100%"}
	]);

	function byId(list, id) {
		for (var i = 0; i < list.length; i++) {
			if (list[i].key == id)
				return list[i].label || "";
		}
		return "";
	}

	//gantt.locale.labels.column_time = "Reales";

	gantt.config.columns = [
		{name: "text", label: "Itemplan", tree: true, width: 190},
		{name: "holder", label: "Responsable", width: 120, align: "center",},
		{name: "avance", label: "Avance", width: 60, align: "center", template: function (item) {
				return byId(gantt.serverList('avanceOptions'), item.avance)
			}, width: 60
		},
		{name: "start_date", label:"Fecha Inicio", align: "center", width: 90},
		{name: "time", label:"Estimado", align: "center", width: 60},
		{name: "duration", label:"Reales", align: "center", width: 60},
		{name: "add", width: 40}
	];
	gantt.locale.labels.section_description = "Descripcion";
	gantt.locale.labels.section_holders = "Responsable";
	gantt.locale.labels.section_avances = "Avance";
	gantt.config.lightbox.sections = [
		{name: "description", 	height: 38, map_to: "text", type: "textarea", focus: true},
		{name: "holders", 	height:22, 	map_to:"holder", type:"textarea", focus: true},
		{name: "avances", 	height: 22, map_to: "avance", type: "select", options: gantt.serverList("avanceOptions")},
		{name: "time", 	type: "duration", map_to: "auto"},
		{name: "priority", height: 22, map_to: "color", type: "select", options: colors},
		{name: "textColor", height: 22, map_to: "textColor", type: "select", options: colors}
	];

	gantt.templates.task_cell_class = function (task, date) {
		if (!gantt.isWorkTime({date: date, task: task}))
			return "week_end";
		return "";
	};

    //agregar texto a la barra
	gantt.templates.task_text = function (start, end, task) {
		var text = [task.text];
		text.push(byId(gantt.serverList('avanceOptions'), task.avance));
		return text.join(", ");
	};

	function updateTaskTiming(task) {
		task.start_date = gantt.getClosestWorkTime({
			dir: "future",
			date: task.start_date,
			unit: gantt.config.duration_unit,
			task: task
		});
		task.end_date = gantt.calculateEndDate(task);
	}

		gantt.attachEvent("onAfterTaskAdd", function (id, task, is_new) {
		updateTaskTiming(task);
		var fec_ini = task.start_date.toLocaleDateString();
		$.ajax({
 	    	type	:	'POST',
 	    	'url'	:	'saveTask',
 	    	data	:   {  datos :	task,
 	 	    			   item : '<?php echo $itemplan?>',
 	 	    			   fecha_ini : fec_ini
 	    				},
 	    	'async'	:	false
 	    })
 	    .done(function(data){/*
 	    	var data	=	JSON.parse(data);                 	    	
 	    	if(data.error == 0){
 	    	 
 			}else if(data.error == 1){
 				
 			}*/	
 		  })
 		  .fail(function(jqXHR, textStatus, errorThrown) {
 	 		  
 		  })
 		  .always(function() {
 	  	 
 			});
 		
		return true;
	});

		gantt.attachEvent("onAfterTaskDelete", function (id, task, is_new) {
			updateTaskTiming(task);
			//console.log(task);
			$.ajax({
	 	    	type	:	'POST',
	 	    	'url'	:	'deleteTask',
	 	    	data	:   {  datos :	task,
	 	 	    			   item : '<?php echo $itemplan?>'
	 	    				},
	 	    	'async'	:	false
	 	    })
	 	    .done(function(data){/*
	 	    	var data	=	JSON.parse(data);                 	    	
	 	    	if(data.error == 0){
	 	    	 
	 			}else if(data.error == 1){
	 				
	 			}*/	
	 		  })
	 		  .fail(function(jqXHR, textStatus, errorThrown) {
	 	 		  
	 		  })
	 		  .always(function() {
	 	  	 
	 			});
			return true;
		});

		gantt.attachEvent("onAfterTaskUpdate", function (id, task, is_new) {
			updateTaskTiming(task);
			//console.log(task);
			var fec_ini = task.start_date.toLocaleDateString();
			$.ajax({
	 	    	type	:	'POST',
	 	    	'url'	:	'updateTask',
	 	    	data	:   {  datos :	task,
	 	 	    			   item : '<?php echo $itemplan?>',
		 	 	    		   fecha_ini : fec_ini
	 	    				},
	 	    	'async'	:	false
	 	    })
	 	    .done(function(data){
	 		  })
	 		  .fail(function(jqXHR, textStatus, errorThrown) {
	 	 		  
	 		  })
	 		  .always(function() {
	 	  	 
	 			});
			return true;
		});

		gantt.attachEvent("onAfterLinkAdd", function (id, task, is_new) {
			//updateTaskTiming(task);
			//console.log(task);
			$.ajax({
	 	    	type	:	'POST',
	 	    	'url'	:	'addLinkTask',
	 	    	data	:   {  datos :	task,
	 	 	    			   item : '<?php echo $itemplan?>'
	 	    				},
	 	    	'async'	:	false
	 	    })
	 	    .done(function(data){
	 		  })
	 		  .fail(function(jqXHR, textStatus, errorThrown) {
	 	 		  
	 		  })
	 		  .always(function() {
	 	  	 
	 			});
			return true;
		});
			
		gantt.attachEvent("onAfterLinkDelete", function (id, task, is_new) {
			//updateTaskTiming(task);
			//console.log(task);
			$.ajax({
	 	    	type	:	'POST',
	 	    	'url'	:	'delLinkTask',
	 	    	data	:   {  datos :	task,
	 	 	    			   item : '<?php echo $itemplan?>'
	 	    				},
	 	    	'async'	:	false
	 	    })
	 	    .done(function(data){
	 		  })
	 		  .fail(function(jqXHR, textStatus, errorThrown) {
	 	 		  
	 		  })
	 		  .always(function() {
	 	  	 
	 			});
			return true;
		});


		
	gantt.init("gantt_here");
	gantt.parse(<?php echo $dataToGant ?>);

	function toggleMode(toggle) {
		toggle.enabled = !toggle.enabled;
		if (toggle.enabled) {
			toggle.innerHTML = "Ver Original";
			//Saving previous scale state for future restore
			saveConfig();
			zoomToFit();
		} else {

			toggle.innerHTML = "Ajustar Gant";
			//Restore previous scale state
			restoreConfig();
			gantt.render();
		}
	}
	
	var cachedSettings = {};

	function saveConfig() {
		var config = gantt.config;
		cachedSettings = {};
		cachedSettings.scale_unit = config.scale_unit;
		cachedSettings.date_scale = config.date_scale;
		cachedSettings.step = config.step;
		cachedSettings.subscales = config.subscales;
		cachedSettings.template = gantt.templates.date_scale;
		cachedSettings.start_date = config.start_date;
		cachedSettings.end_date = config.end_date;
	}

	function restoreConfig() {
		applyConfig(cachedSettings);
	}

	function applyConfig(config, dates) {
		gantt.config.scale_unit = config.scale_unit;
		if (config.date_scale) {
			gantt.config.date_scale = config.date_scale;
			gantt.templates.date_scale = null;
		}
		else {
			gantt.templates.date_scale = config.template;
		}

		gantt.config.step = config.step;
		gantt.config.subscales = config.subscales;

		if (dates) {
			gantt.config.start_date = gantt.date.add(dates.start_date, -1, config.unit);
			gantt.config.end_date = gantt.date.add(gantt.date[config.unit + "_start"](dates.end_date), 2, config.unit);
		} else {
			gantt.config.start_date = gantt.config.end_date = null;
		}
	}


	function zoomToFit() {
		var project = gantt.getSubtaskDates(),
			areaWidth = gantt.$task.offsetWidth;

		for (var i = 0; i < scaleConfigs.length; i++) {
			var columnCount = getUnitsBetween(project.start_date, project.end_date, scaleConfigs[i].unit, scaleConfigs[i].step);
			if ((columnCount + 2) * gantt.config.min_column_width <= areaWidth) {
				break;
			}
		}

		if (i == scaleConfigs.length) {
			i--;
		}

		applyConfig(scaleConfigs[i], project);
		gantt.render();
	}

	// get number of columns in timeline
	function getUnitsBetween(from, to, unit, step) {
		var start = new Date(from),
			end = new Date(to);
		var units = 0;
		while (start.valueOf() < end.valueOf()) {
			units++;
			start = gantt.date.add(start, step, unit);
		}
		return units;
	}

	//Setting available scales
	var scaleConfigs = [
		// minutes
		{
			unit: "minute", step: 1, scale_unit: "hour", date_scale: "%H", subscales: [
				{unit: "minute", step: 1, date: "%H:%i"}
			]
		},
		// hours
		{
			unit: "hour", step: 1, scale_unit: "day", date_scale: "%j %M",
			subscales: [
				{unit: "hour", step: 1, date: "%H:%i"}
			]
		},
		// days
		{
			unit: "day", step: 1, scale_unit: "month", date_scale: "%F",
			subscales: [
				{unit: "day", step: 1, date: "%j"}
			]
		},
		// weeks
		{
			unit: "week", step: 1, scale_unit: "month", date_scale: "%F",
			subscales: [
				{
					unit: "week", step: 1, template: function (date) {
						var dateToStr = gantt.date.date_to_str("%d %M");
						var endDate = gantt.date.add(gantt.date.add(date, 1, "week"), -1, "day");
						return dateToStr(date) + " - " + dateToStr(endDate);
					}
				}
			]
		},
		// months
		{
			unit: "month", step: 1, scale_unit: "year", date_scale: "%Y",
			subscales: [
				{unit: "month", step: 1, date: "%M"}
			]
		},
		// quarters
		{
			unit: "month", step: 3, scale_unit: "year", date_scale: "%Y",
			subscales: [
				{
					unit: "month", step: 3, template: function (date) {
						var dateToStr = gantt.date.date_to_str("%M");
						var endDate = gantt.date.add(gantt.date.add(date, 3, "month"), -1, "day");
						return dateToStr(date) + " - " + dateToStr(endDate);
					}
				}
			]
		},
		// years
		{
			unit: "year", step: 1, scale_unit: "year", date_scale: "%Y",
			subscales: [
				{
					unit: "year", step: 5, template: function (date) {
						var dateToStr = gantt.date.date_to_str("%Y");
						var endDate = gantt.date.add(gantt.date.add(date, 5, "year"), -1, "day");
						return dateToStr(date) + " - " + dateToStr(endDate);
					}
				}
			]
		},
		// decades
		{
			unit: "year", step: 10, scale_unit: "year", template: function (date) {
				var dateToStr = gantt.date.date_to_str("%Y");
				var endDate = gantt.date.add(gantt.date.add(date, 10, "year"), -1, "day");
				return dateToStr(date) + " - " + dateToStr(endDate);
			},
			subscales: [
				{
					unit: "year", step: 100, template: function (date) {
						var dateToStr = gantt.date.date_to_str("%Y");
						var endDate = gantt.date.add(gantt.date.add(date, 100, "year"), -1, "day");
						return dateToStr(date) + " - " + dateToStr(endDate);
					}
				}
			]
		}
	];
	
	function exportGantt(mode) {
		if (mode == "png")
			gantt.exportToPNG({
				header: '<link rel="stylesheet" href="//docs.dhtmlx.com/gantt/samples/common/customstyles.css?v=5.2.0">'
			});
		else if (mode == "pdf")
			gantt.exportToPDF({
				header: '<link rel="stylesheet" href="//docs.dhtmlx.com/gantt/samples/common/customstyles.css?v=5.2.0">'
			});
	}
</script>
</body>	