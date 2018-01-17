<style>
    #settings input {
        height:24px;
    }

    #settings .form-group {
        margin-bottom:5px;
    }
</style>
<template>
    <div id="settings">
        <form-vertical v-for="ps in parsedSettings" :name="ps.slug" :label="ps.name">
            <magis-text :name="ps.slug" v-model="ps.content"></magis-text>
        </form-vertical>
        <button type="button" @click="save" class="btn btn-warning">Save</button>
    </div>
</template>

<script>
	export default {
		props: {
			settings: {
                type: Array | String,
                required: true,
            }
		},
		data() {
			return {
				parsedSettings: Array.isArray(this.settings) ? this.settings : JSON.parse(this.settings)
			}
		},
		methods: {
            save() {
                this.$http.post(url('settings'), this.parsedSettings).then(function () {
                    $.growl.notice({ 
                        title: 'Success',
                        message: 'Settings have been saved' 
                    });
                }, function(error) {
                    App.error(error);
                });
            }
        }
	}
</script>
