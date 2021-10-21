$(document).ready(function(){
	/*INPUT DATA PAGE*/
	function inputDataTable(object){
		for (const keys in object) {
			$(object[keys].id).DataTable({
				scrollX: 550,
				scrollY: 550,
				"processing": true,
				autoWidth :false,
				buttons: [
				{
					extend :'pdfHtml5',
					className : 'btn-success',
					orientation :'landscape',
					text: '<i class="fas fa-file-pdf" aria-hidden="true"></i> PDF',
					title: object[keys].title,
					extension: ".pdf",
					filename: object[keys].filename
				}
				],
				"dom": `
				<'d-flex justify-content-between mb-3 btn-sm' fl> +
                <'d-flex justify-content-end' B>+
				<'mb-3' t> +
				<'d-flex justify-content-start mb-5 mt-3'p>
				`
			});
		}
	}
	const table = {
		table1 : {
			id : "#table_data_original",
			title : "Daftar Data Penelitian",
			filename : "Daftar Data Penelitian"
		},
		table2 : {
			id : "#table_data_normalisasi",
			title : "Daftar Data Penelitian Hasil Normalisasi",
			filename : "Daftar Data Penelitian Hasil Normalisasi"
		},
		table3 : {
			id : "#table_nilai_sse",
			title : "Daftar Nilai Sum Square Error",
			filename : "Daftar Nilai Sum Square Error"
		},
		table4 : {
			id : "#table_metode_kmeans",
			title : "Hasil Klusterisasi Data Menggunakan KMeans",
			filename : "Hasil Klusterisasi Data Menggunakan KMeans"
		},
		table5 : {
			id : "#table_metode_kmeanspso",
			title : "Hasil Klusterisasi Data Menggunakan K-Means PSO",
			filename : "Hasil Klusterisasi Data Menggunakan K-Means PSO"
		},
		table6 : {
			id : "#table_perbandingan_pengujian",
			title : "Perbandingan Hasil Pengujian K-Means dan K-Means PSO",
			filename : "Perbandingan Hasil Pengujian K-Means dan K-Means PSO"
		},
	};
	inputDataTable(table);
	
});