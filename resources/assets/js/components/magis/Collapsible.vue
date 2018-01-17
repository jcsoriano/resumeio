<template>
    <div>
        <div v-show="!shown" @click="show">
            <slot name="showTrigger"></slot>
        </div>
        <div v-show="shown" @click="hide">
            <slot name="hideTriggerBefore" ></slot>
        </div>
        <div v-show="shown">
            <slot name="content"></slot>
        </div>
        <div v-show="shown" @click="hide">
            <slot name="hideTriggerAfter"></slot>
        </div>
    </div>
</template>

<script>
	export default {
		props: {
			showInitially: {
                type: Boolean,
                default: false
            }
		},
		data() {
			return {
				shown: this.showInitially
			}
		},
		methods: {
            show() {
                this.shown = true;
            },
            hide() {
                this.shown = false;
            }
        }
	}
</script>
