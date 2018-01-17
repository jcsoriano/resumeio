<template>
	<div>
        <div class="container">
            <draggable :list="value" class="row" element="div" :options="{group:{name: 'questions', put: false, pull: false}, animation: 100, chosenClass: 'active', disabled: disableDrag}">
                <div v-for="(val, index) in value">
                    <component :is="val.type" :question-type="val.questionType" :question-number="index + 1" :content="val.data" :edit-mode="val.editMode" @isEditMode="isEditMode(value,index)"></component>
                </div>
            </draggable>
            <div class="col-lg-4 offset-lg-4 select-holder">
                <label>Add New Question</label>
                <select class="browser-default form-control section-chooser" id="optionProfile" v-model="chosenSection">
                    <option value="default">Choose your option</option>
                    <option :value="index" v-for="(section, index) in sectionTypes" v-text="section.name"></option>
                </select>
            </div>
        </div>
	</div>
</template>

<script>
    export default {
        props: {

        },

        data() {
            return {
                chosenSection: 'default',
                sectionTypes: [
                    {
                        type: 'form-question',
                        questionType: 'text',
                        name: 'Question (Text)',
                        editMode: true,
                        data: {
                                question: '',
                                answer: '',
                            },

                    },
                    {
                        type: 'form-question',
                        questionType: 'radio',
                        name: 'Question (Radio Buttons)',
                        editMode: true,
                        data: {
                                question: '',
                                answer: '',
                                choices: ['1','2'],
                            },
                    },
                    {
                        type: 'form-question',
                        questionType: 'checkbox',
                        name: 'Question (Checkbox)',
                        editMode: true,
                    },
                ],
                value: [],
            };
        },

        computed: {
            disableDrag() {
                return this.value.filter(val => val.editMode).length > 0;
            },
        },

        watch: {
            chosenSection(newVal) {
                if (newVal != 'default') {
                    var select = this.chosenSection;
                    this.value.push(JSON.parse(JSON.stringify(this.sectionTypes[select])));
                    this.$nextTick(() => {
                        this.chosenSection = 'default';
                    });
                }
            },
        },

        methods: {
            isEditMode(val, index) {
                val[index].editMode = !val[index].editMode;
            },
        },
    };
</script>