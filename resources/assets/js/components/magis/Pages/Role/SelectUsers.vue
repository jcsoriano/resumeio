<template>
    <div id="role_user" class="panel panel-default">
        <div class="panel-heading">
            Users
        </div>
        <div class="panel-body" >
            <input class="form-control text-center" type="text" placeholder="Search Users..." v-model="search" />
            <input type="hidden" name="users" :value="JSON.stringify(selected)">
            <ul class="list-group slim-scroll">
                <li v-for="user in filtered" :class="'list-group-item ' + (user.checked ? 'list-group-item-info' : '')">
                    <a href="#" @click="toggleUser(user, $event)">
                        <div class="media">
                            <div class="media-left col-sm-3">
                                <img class="img-responsive" :src="asset(user.photo)">
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading" v-text="user.name">
                                </h4>
                                <label :class="'label label-' + roleClass(r)" v-for="r in user.roles" v-text="r.name">
                                </label>
                            </div>
                        </div>
                    </a>
                </li>
            </ul>
            <a @click="unSelectAll($event)" class="pull-right btn btn-xs btn-warning">Remove from all</a>
            <a @click="selectAll($event)" class="btn btn-xs btn-info">Add to all</a>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            roleName: {
                required: true,
                type: String,
            },
            users: {
                required: true,
                type: Array | String,
            },
            roleUsers: {
                required: true,
                type: Array | String,
            },
            mode: {
                required: true,
                type: String,
            },
        },

        data() {
            return {
                parsedUsers: Array.isArray(this.users)
                    ? this.users
                    : JSON.parse(this.users),
                parsedRoleUsers: Array.isArray(this.roleUsers)
                    ? this.roleUsers
                    : JSON.parse(this.roleUsers),
                filtered: [],
                search: '',
            };
        },

        created() {
            this.parsedUsers = this.parsedUsers.map((user) => {
                this.$set(user, 'checked', this.parsedRoleUsers.indexOf(user.id) >= 0);
                return user;
            });
            this.filtered = this.parsedUsers;
        },

        watch: {
            search(searchKeyword) {
                if (this.mode == 'heavy') {
                    _.debounce(function () {
                        // coming soon
                    }, 1000);
                } else {
                    if (searchKeyword) {
                        this.filtered = this.parsedUsers.filter(
                            (val) => val.name.includes(searchKeyword)
                        );
                    } else {
                        this.filtered = this.parsedUsers;
                    }
                }
            },

            roleName(newVal, oldVal) {
                this.parsedUsers = this.parsedUsers.map((user) => {
                    user.roles = user.roles.map((role) => {
                        if (role.name == oldVal) {
                            role.name = newVal;
                        }

                        return role;
                    });
                    return user;
                });
            },
        },

        computed: {
            selected() {
                return _.map(this.parsedUsers.filter(user => user.checked), 'id');
            },
        },

        methods: {
            toggleUser(user, e) {
                user.checked = !user.checked;

                if (user.checked) {
                    user.roles.push({
                        id: '',
                        name: this.roleName,
                    });
                } else {
                    var index = _.findIndex(user.roles, { name: this.roleName });
                    user.roles.splice(index, 1);
                }

                if (e) {
                    e.preventDefault();
                }
            },

            asset(path) {
                return url(path);
            },

            roleClass(role) {
                return this.roleName == role.name ? 'primary' : 'default';
            },

            selectAll(e) {
                this.parsedUsers.map((user) => {
                    if (!user.checked) {
                        this.toggleUser(user);
                    }
                });
                e.preventDefault();
            },

            unSelectAll(e) {
                this.parsedUsers.map((user) => {
                    if (user.checked) {
                        this.toggleUser(user);
                    }
                });
                e.preventDefault();
            },
        },
    };
</script>
