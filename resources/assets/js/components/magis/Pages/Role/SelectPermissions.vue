<template>
    <div id="permissions" class="panel panel-default">
        <div class="panel-heading">
            Permissions
        </div>
        <div class="panel-body">
            <div class="row" v-for="og in objectGroups">
                <div class="col-sm-2">
                    <label :for="'object-checkbox-' + og.name">
                        <input :id="'object-checkbox-' + og.name" type="checkbox" v-model="og.show" />
                        <span v-text="humanize(og.name)"></span>
                    </label>
                </div>
                <div v-show="og.show" class="col-sm-10">
                    <div v-for="(p, object) in parsedPermissions[og.name]">
                        <div v-if="Object.keys(parsedPermissions[og.name]).length > 1 && object != og.name" class="col-sm-3">
                            <span v-text="getType(object, og.name)"></span>
                        </div>
                        <div :class="'btn-group ' + (Object.keys(parsedPermissions[og.name]).length > 1 && object != og.name ? 'col-sm-9' : '')" data-toggle="buttons">
                            <div v-for="(permission, label) in p" class="btn-group" data-toggle="buttons">
                                <button type="button" 
                                    :class="'dropdown-toggle btn btn-xs btn-' + btnClass(permission.option)" 
                                    data-toggle="dropdown" 
                                    aria-haspopup="true" 
                                    aria-expanded="false"
                                >
                                    <span v-text="humanize(label)"></span>
                                    <span v-if="permission.option" v-text="'(' + humanize(permission.option) + ')'"></span>
                                    <span class="caret"></span>
                                    <span v-if="permission.option">
                                        <input type="hidden" :name="'permissions[' + permission.name + ']'" v-model="permission.option" />
                                    </span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="#" @click="setOption(og.name, permission, '')">None</a>
                                    </li>
                                    <li v-for="option in parsedOptions">
                                        <a href="#" @click="setOption(og.name, permission, option)" v-text="humanize(option)"></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        permissions: {
            required: true,
            type: Array | String,
        },
        options: {
            required: true,
            type: Array | String,
        },
        rolePermissions: {
            required: true,
            type: Array | String,
        },
    },
    data() {
        return {
            parsedPermissions: Array.isArray(this.permissions)
                ? this.permissions
                : JSON.parse(this.permissions),
            parsedOptions: Array.isArray(this.options)
                ? this.options
                : JSON.parse(this.options),
            parsedRolePermissions: Array.isArray(this.rolePermissions)
                ? this.rolePermissions
                : JSON.parse(this.rolePermissions),
            objectGroups: [],
        };
    },

    mounted() {
        var rolePermissions = this.parsedRolePermissions;

        this.parsedPermissions = _.mapValues(this.parsedPermissions,
            (objects, mainObject) => _.mapValues(objects,
                (permissionsInObject, object) => _.mapValues(permissionsInObject,
                    (permission, action) => ({
                        name: permission,
                        option: rolePermissions[permission],
                    })
                )
            )
        );

        this.objectGroups = _.mapValues(this.parsedPermissions,
            (objectGroup, objectGroupName) => ({
                name: objectGroupName,
                show: this.objectShow(objectGroup),
            })
        );
    },

    methods: {
        humanize(val) {
            return _.startCase(val);
        },

        getType(val, mainObject) {
            var mainObject = _.singularize(mainObject);
            return this.humanize(_.trim(val.substr(mainObject.length).replace('_', ' ')));
        },

        setOption(object, permission, option) {
            var index = _.findIndex(this.parsedPermissions[object], permission);
            permission.option = option;
        },

        btnClass(option) {
            return !option ? 'default' : option == 'all' ? 'warning' : 'info';
        },

        objectShow(objectGroup) {
            for (const i in objectGroup) {
                var object = objectGroup[i];
                for (const j  in object) {
                    var permission = object[j];
                    if (permission.option) {
                        return true;
                    }
                }
            }

            return false;
        },
    },
};
</script>
