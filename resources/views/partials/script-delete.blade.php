<script>
    $(() => {
        $('body').on('click','a.btn-delete-datatable', function(e) {
            let that = $(e.currentTarget);
            e.preventDefault()
            Swal.fire({
                title: 'Apakah Anda yakin ingin menghapus data {{ $text }} ini?',
                type: 'warning',
                confirmButtonText: 'Ya',
                showCancelButton: true,
                cancelButtonText: 'Tidak',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        headers: {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: that.attr('href'),
                        type: 'DELETE'
                    })
                    .done(() => {
                        Swal.fire({
                            title: 'Data {{ $text}} berhasil dihapus',
                            type: 'success'
                        }).then((nextResult) => {
                          if (nextResult.value) {
                            $('{{ $table }}').DataTable().ajax.reload();
                          }                        
                        })
                    })
                    .fail(() => {
                        Swal.fire({
                            title: 'Terjadi kesalahan dalam menghubungi server, silahkan coba lagi...',
                            type: 'warning'
                        })
                    })
                }
            })
        })
    })
</script>