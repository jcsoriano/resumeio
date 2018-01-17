<template>
	<div>
        <div :class="'big-picture hm-white-strong z-depth-1 ' + (!notShowOptions ? 'waves-effect waves-light' : '')" :style="'background: url(' + url(value) + ') no-repeat center center;'" >
            <div class="overlay option-holder" v-if="!notShowOptions">
                <div class="options" @click="editValue()">
                    <div class="options-links text-xs-center">
                        <p>
                            <i class="fa fa-camera"></i>
                            &nbsp;
                            Upload A Picture
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!--Modals here-->
        <div class="modal fade" id="bannerUpload" tabindex="-1" role="dialog" aria-labelledby="bannerUploadLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 class="modal-title" id="bannerUploadLabel">Upload Banner Picture</h5>
                    </div>
                    <div class="modal-body">
                        <!-- <div class="md-form">
                            <input type="text" id="bannerUploadInput" v-model="tempValue" class="form-control">
                            <label for="bannerUploadInput" class="">Enter Link</label>
                        </div> -->
                        <magis-photo v-model="tempValue" :path="path" no-gallery ></magis-photo>
                    </div>
                    <div class="modal-footer">
                        <button type="button" @click="cancelSaveValue" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                        <button type="button" @click="saveNewValue" class="btn btn-primary btn-sm">Save</button>
                    </div>
                </div>
            </div>
        </div>
	</div>
</template>

<script>
    export default {
        props: {
            notShowOptions: {
                required: true,
                default: false,
            },
            value: {
                required: false,
                // default: 'https://source.unsplash.com/category/nature',
                default: url('img/default.jpg'),
            },
            path: {
                type: String,
                required: true,
            },
        },

        data() {
            return {
                tempValue: this.value,
            };
        },

        methods: {
            editValue: function () {
                $('#bannerUpload').modal();
                $('#bannerUpload').on('shown.bs.modal', function (e) {
                    // document.getElementById('bannerUploadInput').focus();
                });
            },

            saveNewValue: function () {
                if (this.tempValue != '') {
                    this.$emit('input', this.tempValue);
                    $('#bannerUpload').modal('hide');
                }
            },

            cancelSaveValue: function () {
                $('#bannerUpload').modal('hide');
            },

            url(path) {
                return url(path);
            },
        },
    };
</script>