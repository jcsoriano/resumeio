<template>
    <div>
        <draggable :list="value.data" element="div" :options="{group: {name: value.type+'-'+index, put: false, pull: false}, animation: 100, chosenClass: 'active', disabled: disableDrag}">
            <div class="row" v-for="(opdata, opindex) in value.data">
                <component :is="value.question ? 'bullet-question' : 'bullet-item'" :bullet-name="value.bulletName" :not-show-options="notShowOptions" :value="opdata" :with-date-range="value.withDateRange" @removeItem="removeItem(value.data, opindex)" :index="opindex" :item-type="value.type">
                </component>
            </div>
        </draggable> 
        <div class="text-xs-center" v-if="!notShowOptions">
            <button class="btn btn-primary btn-sm" @click="addBulletItem(value)">Add New <span v-text="value.bulletName ? value.bulletName : 'Item'" ></span></button>
        </div>
    </div>
</template>
<script>
    export default {
        // props: ['value','notShowOptions']
        props: {
            notShowOptions: {
                type: Boolean,
                default: true,
            },
            value: {
                type: Object,
                required: false,
            },
            index: {
                required: true,
                default: 0,
            },
        },

        beforeMount() {
            this.setValueIfUndefined('editName', false);
            this.setValueIfUndefined('disableDrag', false);
        },

        data() {
            return {
                disableDrag: false,
            };
        },

        computed: {
            disableDrag() {
                return this.value.data.filter(exp => exp.editing).length > 0
                    || this.notShowOptions;
            },
        },

        watch: {
            disableDrag(newVal) {
                this.value.disableDrag = newVal;
            },
        },

        methods: {
            setValueIfUndefined(property, defaultValue) {
                if (this.value[property] === undefined) {
                    this.$set(this.value, property, defaultValue);
                }
            },

            removeItem: function (secdata, index) {
                secdata.splice(index, 1);
            },

            addBulletItem: function (value) {
                let toPush;
                if (this.value.question) {
                    toPush = {
                        editing: true,
                        question: '',
                        type: 'magis-string',
                        answer: '',
                        saved: false,
                    };
                } else {
                    toPush = {
                        editing: true,
                        dateTo: '',
                        dateFrom: '',
                        title: '',
                        description: '',
                        saved: false,
                    };
                }
                
                value.data.push(toPush);
            },
        },
    };
</script>