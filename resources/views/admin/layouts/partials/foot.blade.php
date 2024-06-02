<script src="{{ asset('src/plugins/src/global/vendors.min.js') }}"></script>
<script src="{{ asset('src/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('src/plugins/src/mousetrap/mousetrap.min.js') }}"></script>
<script src="{{ asset('src/plugins/src/waves/waves.min.js') }}"></script>
<script src="{{ asset('layouts/vertical-dark-menu/app.js') }}"></script>
<script src="{{ asset('src/assets/js/custom.js') }}"></script>

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ asset('src/plugins/src/table/datatable/datatables.js') }}"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<script src="{{ asset('src/plugins/src/highlight/highlight.pack.js') }}"></script>
<!-- END GLOBAL MANDATORY STYLES -->

<!--  BEGIN CUSTOM SCRIPT FILE  -->
<script src="{{ asset('src/assets/js/scrollspyNav.js') }}"></script>
<script src="{{ asset('src/plugins/src/filepond/filepond.min.js') }}"></script>
<script src="{{ asset('src/plugins/src/filepond/FilePondPluginFileValidateType.min.js') }}"></script>
<script src="{{ asset('src/plugins/src/filepond/FilePondPluginImageExifOrientation.min.js') }}"></script>
<script src="{{ asset('src/plugins/src/filepond/FilePondPluginImagePreview.min.js') }}"></script>
<script src="{{ asset('src/plugins/src/filepond/FilePondPluginImageCrop.min.js') }}"></script>
<script src="{{ asset('src/plugins/src/filepond/FilePondPluginImageResize.min.js') }}"></script>
<script src="{{ asset('src/plugins/src/filepond/FilePondPluginImageTransform.min.js') }}"></script>
<script src="{{ asset('src/plugins/src/filepond/filepondPluginFileValidateSize.min.js') }}"></script>
@stack('js-src')

@stack('js')

