$(document).ready(() => {
	initDataTable("#tbFirmaEmpresaColab");
});

function openAddFirmaEmpresaColab() {
	$.ajax({
		type: "POST",
		url: "getDataFormFirmaEmpresaColab",
	}).done(function (data) {
		data = JSON.parse(data);

		if (data.error == 0) {
			let htmlCboUsuarioFirma =
				"<option value='' data-nombre='' data-id-eecc='' data-eecc=''>Seleccione</option>";
			$.each(data.cboUsuarioByFirmaDigital, function (i, e) {
				htmlCboUsuarioFirma += `<option value='${e.id_usuario}' data-nombre='${e.nombre}' data-id-eecc=${e.id_eecc} data-eecc='${e.empresaColabDesc}' >${e.usuario}</option>`;
			});

			$("#cboUsuarioFirmaEmpresaColab").html(htmlCboUsuarioFirma);
			modal("modalAddFirmaEmpresaColab");

			$(".select2").select2();
		}
	});
}

function handlePrintUser(element) {
	let { nombre, idEecc, eecc } = $(element).find(":selected").data();

	$("#txtUsuarioFirma").text(nombre);
	$("#txtEmpresaColabFirma").text(eecc);
	$("#guardarFirmaEmpresaCola").data("id-eecc", idEecc);
}

function saveFirmaDig(element) {
	let { idEecc } = $(element).data();

	let usuarioFirmaEmpresaColab = $(
		"#cboUsuarioFirmaEmpresaColab option:selected"
	).val();
	let tipoPlantaFirmaEmpresaColab = $(
		"#cboTipoPlantaFirmaEmpresaColab option:selected"
	).val();

	if ($(`#chkActivoFirmaEmpresaColab`).is(":checked")) {
		$(`#chkActivoFirmaEmpresaColab`).val("1");
	} else {
		$(`#chkActivoFirmaEmpresaColab`).val("0");
	}

	if ($(`#chkDespliegueFirma`).is(":checked")) {
		$(`#chkDespliegueFirma`).val("2");
	} else {
		$(`#chkDespliegueFirma`).val("");
	}

	let activoFirmeEmpresaColab = $("#chkActivoFirmaEmpresaColab").val();
	let chkDespliegueFirma = $("#chkDespliegueFirma").val();

	if (usuarioFirmaEmpresaColab == "") {
		mostrarNotificacion("warning", "Valida", "Ingresar el usuario");
		return;
	}

	if (tipoPlantaFirmaEmpresaColab == "") {
		mostrarNotificacion("warning", "Valida", "Ingresar el tipo de planta");
		return;
	}

	let formData = new FormData();
	formData.append("usuarioFirmaEmpresaColab", usuarioFirmaEmpresaColab);
	formData.append("idEecc", idEecc);
	formData.append("tipoPlantaFirmaEmpresaColab", tipoPlantaFirmaEmpresaColab);
	formData.append("activoFirmeEmpresaColab", activoFirmeEmpresaColab);
	formData.append("chkDespliegueFirma", chkDespliegueFirma);

	$.ajax({
		type: "POST",
		url: "registrarFirmaEmpresaColab",
		data: formData,
		contentType: false,
		processData: false,
		cache: false,
	}).done(function (data) {
		data = JSON.parse(data);

		if (data.error == 0) {
			$("#contTablaFirmaEmpresaColab").html(data.getTablaFirmaEmpresaColab);
			initDataTable("#tbFirmaEmpresaColab");

			$("#txtUsuarioFirma").text("");
			$("#txtEmpresaColabFirma").text("");
			$("#cboTipoPlantaFirmaEmpresaColab").val("");
			$("#chkActivoFirmaEmpresaColab").prop("checked", true);
			$("#chkDespliegueFirma").prop("checked", false);

			modal("modalAddFirmaEmpresaColab");

			mostrarNotificacion("success", "Aviso", "Se registró correctamente");
		} else {
			mostrarNotificacion("error", "Aviso", data.msj);
		}
	});
}


function updateFirmaDig(element) {
	let { idEmpresaColab, idUsuario, option } = $(element).data();

	if (parseInt(option) == 1) {
		$(`#cboTipoPlantaFirma${idEmpresaColab}-${idUsuario}`).attr("disabled", false);
		$(`#chkDespliegueFirma${idEmpresaColab}-${idUsuario}-2`).attr("disabled", false);
		$(`#cboEstadoFirma${idEmpresaColab}-${idUsuario}`).attr("disabled", false);

		$(element).data("option", 2);
	} else {
		let cboTipoPlantaFirma = $(`#cboTipoPlantaFirma${idEmpresaColab}-${idUsuario}`).val();
		let cboEstadoFirma = $(`#cboEstadoFirma${idEmpresaColab}-${idUsuario}`).val();

		if ($(`#chkDespliegueFirma${idEmpresaColab}-${idUsuario}-2`).is(":checked")) {
			$(`#chkDespliegueFirma${idEmpresaColab}-${idUsuario}-2`).val("2");
		} else {
			$(`#chkDespliegueFirma${idEmpresaColab}-${idUsuario}-2`).val("");
		}

		if (cboTipoPlantaFirma == "") {
			mostrarNotificacion(
				"warning",
				"Validar",
				"Seleccionar el tipo de planta"
			);
			return;
		}

		let chkDespliegueFirma = $(`#chkDespliegueFirma${idEmpresaColab}-${idUsuario}-2`).val();

		if (chkDespliegueFirma == "") {
			mostrarNotificacion(
				"warning",
				"Validar",
				"Seleccionar la gerencia"
			);
			return;
		}

		let formData = new FormData();
		formData.append("idEmpresaColab", idEmpresaColab);
		formData.append("idUsuario", idUsuario);
		formData.append("tipoPlantaFirma", cboTipoPlantaFirma);
		formData.append("estadoFirma", cboEstadoFirma);
		formData.append("chkDespliegueFirma", chkDespliegueFirma);

		$.ajax({
			type: "POST",
			url: "actualizarFirmaEmpresaColab",
			data: formData,
			contentType: false,
			processData: false,
			cache: false,
		}).done(function (data) {
			data = JSON.parse(data);

			if (data.error == 0) {
				$("#contTablaFirmaEmpresaColab").html(data.getTablaFirmaEmpresaColab);
				initDataTable("#tbFirmaEmpresaColab");

				$(element).data("option", 1);
			}
		});
	}
}
