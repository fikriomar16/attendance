const base = angular.element('body').data('home');
const table = angular.element('#dataFilterTable');
const source = table.data('source');
const url = angular.element('#formFilter').attr('action');
let config = {
	enableTime: true,
	enableSeconds: true,
	dateFormat: "Y-m-d H:i:S",
	time_24hr: true,
	defaultHour: 7
};
let tbodyTr;
const app = angular.module('ScanLog', []);
app.controller('ScanLog',($scope,$http) => {
	const fp = flatpickr('.input-time',config);
	fp[0];
	table.DataTable({
		'sDom': 'tr',
		'bSort': false,
		"pageLength": 100,
		"processing": true,
		"serverSide": true,
		"order": [],
		"ajax": {
			"url": source,
			"type": "POST"
		},
		"columnDefs": [{ 
			"targets": [],
			"orderable": false
		}]
	});
	$scope.search = () => {
		table.DataTable().search($scope.searchInTable).draw();
	}
	$scope.reloadTable = () => {
		table.DataTable().ajax.reload();
	}
	$scope.loadEmpList = () => {
		angular.element('.alert-loading').removeClass('d-none');
		angular.element('#pin').selectpicker('destroy');
		$http.get(base+'scanlog/getPinName').then((res) => {
			angular.element('.alert-loading').addClass('d-none');
			$scope.emps = res.data;
			setTimeout(() => {
				angular.element('#pin').selectpicker('refresh');
			},1000);
		});
	}
	$scope.loadEmpList();
	$scope.getFilterData = (e) => {
		$http({
			method:"POST",
			url:url,
			data:{
				'masuk':$scope.masuk,
				'pulang':$scope.pulang,
				'pin':$scope.pin
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
				$http.get(angular.element('#dataResumeTable').data('source')).then((rslt) => {
					console.log(rslt.data);
					$scope.reloadTable();
					angular.element('.divFilterTab').removeClass('d-none');
					if (rslt.data !== 'null') {
						tbodyTr = '';
						tbodyTr+= '<tr>';
						tbodyTr+= '<td class="text-primary font-weight-bold">';
						tbodyTr+= rslt.data.first_scan;
						tbodyTr+= '</td>';
						tbodyTr+= '<td class="text-primary font-weight-bold">';
						tbodyTr+= rslt.data.last_scan;
						tbodyTr+= '</td>';
						tbodyTr+= '</tr>';
						angular.element('#fillResumeTab').html(tbodyTr);
						angular.element('.divResumeTab').removeClass('d-none');
					} else {
						tbodyTr = '';
						tbodyTr+= '<tr>';
						tbodyTr+= '<td class="text-danger font-weight-bold text-center" colspan=2>';
						tbodyTr+= 'Data Not Found';
						tbodyTr+= '</td>';
						tbodyTr+= '</tr>';
						angular.element('#fillResumeTab').html(tbodyTr);
						angular.element('.divResumeTab').removeClass('d-none');
					}
				});
			}
		}),(err) => {
			console.error(err);
			errorNotif('Terjadi Sebuah Kesalahan');
		};;
	}
});