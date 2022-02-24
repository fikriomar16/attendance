const base = angular.element('body').data('home');
const table = angular.element('#dataTable');
const source = table.data('source');
const table_sch = angular.element('#schTable');
const source_sch = table_sch.data('source');
const url = angular.element('#scheduleForm').attr('action');
const app = angular.module('schEmp', []);
const today = new Date();
const minDate = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
const maxDate = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+(today.getDate()+6);
const defMonth = today.getMonth()+1;
let config = {
	enableTime: true,
	enableSeconds: true,
	dateFormat: "Y-m-d H:i:S",
	time_24hr: true,
	defaultHour: 7,
	minDate: minDate,
	maxDate: maxDate
};
let tbodyTr;
app.controller('schEmp',($scope,$http) => {
	$scope.importData = [];
	$http.get(base+'schedule/empList').then((res) => {
		$scope.emps = res.data;
	});
	$scope.closeSch = () => {
		angular.element('.card-schedule').addClass('d-none');
	}
	$scope.closeAdd = () => {
		angular.element('.card-new').addClass('d-none');
		angular.element('#scheduleForm')[0].reset();$scope.id_sch = '';
	}
	const fp = flatpickr('.input-time',config);
	fp[0];
	table.DataTable({
		"sDom" : 'tipr',
		"processing": true,
		"serverSide": true,
		"responsive": true,
		"order": [],
		"ajax": {
			"url": source,
			"type": "POST"
		},
		"columnDefs": [{ 
			"targets": [ 0,-1 ],
			"orderable": false
		}]
	});
	$scope.search = () => {
		table.DataTable().search($scope.searchInTable).draw();
	}
	$scope.reloadTable = () => {
		table.DataTable().ajax.reload();
	}
	table_sch.DataTable({
		"sDom" : 'tr',
		"bSort" : false,
		"processing": true,
		"serverSide": true,
		"responsive": true,
		"order": [],
		"ajax": {
			"url": source_sch,
			"type": "POST"
		},
		"columnDefs": [{ 
			"targets": [ 0,-1 ],
			"orderable": false
		}]
	});
	$scope.getsch = (nik) => {
		angular.element('.card-schedule').removeClass('d-none');
		$scope.schId = nik;
		$scope.getNIK = nik;
		$http.get(angular.element('.card-schedule').data('source')+$scope.schId).then((res) => {
			$scope.getSchName = res.data.getSchName;
			$scope.getSchDate= res.data.getSchDate;
			table_sch.DataTable().ajax.reload();
			angular.element('.scroll-to-top').click();
		});
	}
	$scope.getToday = () => {
		$http.get(base+'sch_emp_tdy').then((res) => {
			table_sch.DataTable().ajax.reload();
			$scope.getSchDate= res.data.date;
		});
	}
	$scope.getYesterday = () => {
		$http.get(base+'sch_emp_ytd').then((res) => {
			table_sch.DataTable().ajax.reload();
			$scope.getSchDate= res.data.date;
		});
	}
	$scope.getTomorrow = () => {
		$http.get(base+'sch_emp_tmr').then((res) => {
			table_sch.DataTable().ajax.reload();
			$scope.getSchDate= res.data.date;
		});
	}
	$scope.newSchedule = () => {
		angular.element('#scheduleForm')[0].reset();
		document.getElementById('nik').value = '';
		$scope.id_sch = '';
		$scope.nik = '';
		$scope.shift = '';
		$scope.masuk = '';
		$scope.pulang = '';
		angular.element('.info-emp').addClass('d-none');
		angular.element('.select-emp').removeClass('d-none');
		angular.element('#nik').selectpicker('destroy');
		angular.element('#nik').selectpicker('refresh');
		$scope.getTitle = 'Add New Schedule';
		angular.element('.card-new').removeClass('d-none');
		fp[0].clear();
		flatpickr('.input-time',config);
		angular.element('.scroll-to-top').click();
	}
	$scope.saveSchedule = () => {
		$http({
			method:"POST",
			url:url,
			data:{
				'id':$scope.id_sch,
				'nik':$scope.nik,
				'shift':$scope.shift,
				'masuk':$scope.masuk,
				'pulang':$scope.pulang
			}
		}).then((res) => {
			if (res.data.error) {
				var list = '';
				var loop = 0;
				while (loop < res.data.error.length) {
					list+='<ul class="text-left">';
					list+='<li>';
					list+=res.data.error[loop];
					list+='</li>';
					list+='</ul>';
					loop++;
				}
				listErrorNotif(list);
			} else if (res.data.success) {
				table_sch.DataTable().ajax.reload();
				successPopUp(res.data.success);
				$scope.closeAdd();
			}
		}),(err) => {
			console.error(err);
			errorNotif('Terjadi Sebuah Kesalahan');
		};
	}
	$scope.edit = (id) => {
		fp[0].destroy();
		angular.element('.alert-loading').removeClass('d-none');
		$scope.id_sch = id;
		$scope.getTitle = 'Edit Schedule';
		angular.element('.info-emp').removeClass('d-none');
		angular.element('.select-emp').addClass('d-none');
		$http.get(base+'get_by_id_employee_sch/'+id).then((res) => {
			angular.element('.card-new').removeClass('d-none');
			angular.element('.alert-loading').addClass('d-none');
			$scope.empNik = res.data.nik;
			$scope.empName = res.data.nama;
			$scope.shift = res.data.shift;
			$scope.masuk = res.data.masuk;
			$scope.pulang = res.data.pulang;
			document.getElementById('masuk').value = res.data.masuk;
			document.getElementById('pulang').value = res.data.pulang;
			document.getElementById('shift').value = res.data.shift;
			var getDate = new Date(res.data.masuk);
			var getStart = getDate.getFullYear()+'-'+(getDate.getMonth()+1)+'-'+getDate.getDate();
			var getEnd = getDate.getFullYear()+'-'+(getDate.getMonth()+1)+'-'+(getDate.getDate()+6);
			flatpickr('.input-time',{
				enableTime: true,
				enableSeconds: true,
				dateFormat: "Y-m-d H:i:S",
				time_24hr: true,
				defaultHour: 7,
				minDate: getStart,
				maxDate: getEnd
			});
			$scope.nik = res.data.nik;
			angular.element('.scroll-to-top').click();
		});
	}
	$scope.delete = (id) => {
		angular.element('.alert-loading').removeClass('d-none');
		angular.element('.scroll-to-top').click();
		$scope.closeAdd();
		Swal.fire({
			title: 'Yakin ingin menghapus?',
			text: "Proses ini tidak bisa dibatalkan!",
			icon: 'warning',
			showCancelButton: true,
			cancelButtonText: 'Batalkan',
			confirmButtonText: 'Ya, hapus!'
		}).then((result) => {
			angular.element('.alert-loading').addClass('d-none');
			if (result.isConfirmed) {
				$http.get(base+'deleteSchedule/'+id).then((res) => {
					if (res.data.error) {
						errorNotif(res.data.error);
					} else if (res.data.success) {
						table_sch.DataTable().ajax.reload();
						successPopUp(res.data.success);
					}
				});
			}
		});
	}
	$scope.csvButton = () => {
		Swal.fire({
			title: 'Apakah anda sudah memiliki template untuk import?',
			icon: 'info',
			showDenyButton: true,
			showCancelButton: true,
			confirmButtonText: 'Ya, Sudah',
			denyButtonText: 'Belum Punya',
		}).then((result) => {
			if (result.isConfirmed) {
				angular.element('.import_sch').click();
			} else if (result.isDenied) {
				infoPopUp('Mengunduh Template....');
				location.href = base+'exportSchTemplate';
			}
		})
	}
	$scope.doImportCSV = (files) => {
		infoPopUp('Mengimport Data....');
		var fd = new FormData();
		fd.append("import_sch", files[0]);
		$http.post(angular.element('#importForm').attr('action'),fd,{
			withCredentials: true,
			headers: {'Content-Type': 'multipart/form-data' },
			headers: {'Content-Type': undefined },
			transformRequest: angular.identity
		}).then((res) => {
			$scope.importData = [];
			if (res.data.error) {
				errorPopUp(res.data.error);
			}
			if (res.data.success) {
				if (res.data.collect.length == 0) {
					errorPopUp('Data Kosong atau Sudah Terimport');
				} else {
					var loop = 0;
					tbodyTr = '';
					while (loop < res.data.collect.length) {
						tbodyTr+= '<tr>';
						tbodyTr+= '<td class="text-primary font-weight-bold">';
						tbodyTr+= res.data.collect[loop].nik;
						tbodyTr+= '</td>';
						tbodyTr+= '<td>';
						tbodyTr+= res.data.collect[loop].nama;
						tbodyTr+= '</td>';
						tbodyTr+= '<td>';
						tbodyTr+= res.data.collect[loop].shift;
						tbodyTr+= '</td>';
						tbodyTr+= '<td>';
						tbodyTr+= res.data.collect[loop].tanggal;
						tbodyTr+= '</td>';
						tbodyTr+= '<td>';
						tbodyTr+= res.data.collect[loop].masuk;
						tbodyTr+= '</td>';
						tbodyTr+= '<td>';
						tbodyTr+= res.data.collect[loop].pulang;
						tbodyTr+= '</td>';
						tbodyTr+= '</tr>';
						loop++;
					}
					$scope.importData = res.data.collect;
					angular.element('#table-import').html(tbodyTr);
					successPopUp(res.data.success);
					angular.element('#modalImport').modal('show');
				}
			}
		}),(err) => {
			console.log(err);
		};
		angular.element('#importForm')[0].reset();
		document.getElementById("import_sch").value = "";
	}
	$scope.importFromModal = () => {
		$http({
			method:"POST",
			url:base+'processImport',
			data:$scope.importData
		}).then((res) => {
			if (res.data.error) {
				errorNotif(res.data.error);
			} else if (res.data.success) {
				successPopUp(res.data.success);
				table_sch.DataTable().ajax.reload();
				angular.element('#modalImport').modal('hide');
			}
		}),(err) => {
			console.log(err);
		};
	}
});