const Toast = Swal.mixin({
	toast: true,
	position: 'top-end',
	showConfirmButton: false,
	timer: 6000,
	timerProgressBar: true,
	didOpen: (toast) => {
		toast.addEventListener('mouseenter', Swal.stopTimer)
		toast.addEventListener('mouseleave', Swal.resumeTimer)
	}
})
const successPopUp = (msg) => {
	Toast.fire({
		icon: 'success',
		title: msg
	})
}
const errorPopUp = (msg) => {
	Toast.fire({
		icon: 'error',
		title: msg
	})
}
const errorNotif = (msg) => {
	Swal.fire({
		icon: 'error',
		title: 'Peringatan!!',
		text: msg,
		showConfirmButton: false,
		timer: 2000
	})
}