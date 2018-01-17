<template>
	<div>
        <div class="circle-holder">
            <div class="circle-picture waves-effect waves-light z-depth-1" :class="!this.disableOtherButtonExceptSave && !notShowOptions ? '' : 'hoverable'">
                <img :src="url(value)">
                <div class="overlay camera-holder" v-if="!this.disableOtherButtonExceptSave && !notShowOptions" @click="editValue()">
                    <i class="fa fa-camera"></i>
                </div>
            </div>
        </div>
        <div class="modal fade" id="profileUpload" tabindex="-1" role="dialog" aria-labelledby="profileUploadLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <!--Content-->
                <div class="modal-content">
                    <!--Header-->
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 class="modal-title" id="bannerUploadLabel">Upload Profile Picture</h5>
                    </div>
                    <!--Body-->
                    <div class="modal-body">
                        <!-- <div class="md-form"> -->
                            <!-- <input type="text" id="profileUploadInput" v-model="tempValue" class="form-control"> -->
                            <magis-photo v-model="tempValue" :path="path" no-gallery></magis-photo>
                            <!-- <label for="profileUploadInput" class="">Enter Link</label> -->
                        <!-- </div> -->
                    </div>
                    <!--Footer-->
                    <div class="modal-footer">
                        <button type="button" @click="cancelProfilePicture" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                        <button type="button" @click="saveProfilePicture" class="btn btn-primary btn-sm">Save</button>
                    </div>
                </div>
                <!--/.Content-->
            </div>
        </div>
	</div>
</template>

<script>
    export default {
        props: {
            notShowOptions: {
                type: Boolean,
                default: true,
            },
            value: {
                type: String,
                default: url('img/user.png'),
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
                $('#profileUpload').modal();
                $('#profileUpload').on('shown.bs.modal', function (e) {
                    // document.getElementById('profileUploadInput').focus();
                });
            },

            saveProfilePicture: function () {
                if (this.tempValue != '') {
                    this.$emit('input', this.tempValue);
                    $('#profileUpload').modal('hide');
                }
            },

            cancelProfilePicture: function () {
                $('#profileUpload').modal('hide');
            },

            url(path) {
                return url(path);
            },
        },
    };
</script>