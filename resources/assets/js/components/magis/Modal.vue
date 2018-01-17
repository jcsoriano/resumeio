<template>
    <div :id="id" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static">
        <div :class="'modal-dialog ' + sizeModifier" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" @click="close" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <slot name="modal-title"></slot>
                </div>
                <slot name="modal-body"></slot>
                <slot name="modal-footer"></slot>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            id: {
                type: String,
                default: 'modal',
            },
            show: {
                type: Boolean,
                default: false,
            },
            sizeModifier: {
                type: String,
                default: '',
            },
        },

        watch: {
            show(newVal) {
                if (newVal) {
                    $('#' + this.id).modal('show');
                } else {
                    $('#' + this.id).modal('hide');
                }
            },
        },

        methods: {
            close() {
                this.$emit('close');
            },
        },
    };
</script>
