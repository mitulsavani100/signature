<script type="text/javascript" src="assets/js/plugins/tables/datatables/datatables.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4><i class="fa fa-comments-o"></i> <span class="text-semibold">Feedback</span></h4>
        </div>
    </div>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('home'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Feedback</li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <?php
            if ($this->session->flashdata('success')) {
            ?>
                <div class="alert alert-success hide-msg">
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                    <strong><?php echo $this->session->flashdata('success') ?></strong>
                </div>
            <?php } elseif ($this->session->flashdata('error')) {
            ?>
                <div class="alert alert-danger hide-msg">
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                    <strong><?php echo $this->session->flashdata('error') ?></strong>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="panel panel-flat">
        <table class="table datatable-basic">
            <thead>
                <tr>
                    <th>Sr No.</th>
                    <th>User Profile</th>
                    <th>Name</th>
                    <!--<th>Reason</th>-->
                    <th>Description</th>
                    <th>Rating</th>
                    <th>Given On</th>
                </tr>
            </thead>
        </table>
    </div>
    <?php $this->load->view('Templates/footer'); ?>
</div>
<script>
    $(function() {

        $('.datatable-basic').dataTable({
            autoWidth: false,
            processing: true,
            serverSide: true,
            language: {
                search: '<span>Filter:</span> _INPUT_',
                lengthMenu: '<span>Show:</span> _MENU_',
                paginate: {
                    'first': 'First',
                    'last': 'Last',
                    'next': '&rarr;',
                    'previous': '&larr;'
                }
            },
            dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
            order: [
                [5, "desc"]
            ],
            ajax: site_url + 'feedback/get_feedbacks',
            columns: [{
                    data: "sr_no",
                    visible: true,
                    sortable: false,
                },
                {
                    data: "profile_pic",
                    visible: true,
                    sortable: false,
                    render: function(data, type, full, meta) {
                        var profile_img = '';
                        if (full.profile_pic != '') {
                            profile_img = '<img src="' + profile_img_url + full.profile_pic + '" height="55px" width="55px" alt="' + full.name + '">';
                        } else {
                            profile_img = '<img src="assets/images/no_logo.png" height="55px" width="55px" alt="' + full.name + '">';
                        }
                        return profile_img;
                    }
                },
                {
                    data: "name",
                    visible: true,
                },
                //                {
                //                    data: "reason",
                //                    visible: true
                //                },
                {
                    data: "description",
                    visible: true
                },
                {
                    data: "rating",
                    visible: true,
                    render: function(data, type, full, meta) {
                        var rate = full.rating + '/10';
                        return rate;
                    }
                },
                {
                    data: "created",
                    visible: true,
                }
            ]
        });

        $('.dataTables_length select').select2({
            minimumResultsForSearch: Infinity,
            width: 'auto'
        });
    });
</script>