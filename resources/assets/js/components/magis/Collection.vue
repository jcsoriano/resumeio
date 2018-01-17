<style scoped>
    .pagination {
        margin:0px;
    }
</style>

<template>
	<div>
		<div class="row">
			<div :class="perPageClass">
				<select class="form-control" v-if="perPageChoices.indexOf(perPage) >= 0" v-model="perPage">
                    <option v-for="ppc in perPageChoices" :value="ppc" v-text="ppc + ' rows'"></option>
				</select>
			</div>
			<div :class="searchClass">
				<input type="text" class="form-control" v-model="search" @keyup.enter="sync" :placeholder="heavy ? 'Search: Type keywords and press Enter' : 'Search keywords...'" />
			</div>
		</div>
		<div class="row">
            <component 
                :items="paginated" 
                :type-map="typeMap"
                :resource="resource" 
                :is="'magis-' + collectionType" 
                :relation-attributes="relationAttributes"
                :type="subtype" 
                :tr-type="trType"
                :columns="columns" 
                :display-columns="displayColumns"
                :real-time-columns="realTimeColumns"
                :form="form" 
                :heavy="heavy" 
                :permissions="permissions" 
                :buttons="buttons" 
                :sort-by="sortBy"
            >
            </component>
		</div>
		<div class="row">
            <div :class="statusClass">
                Showing <span v-text="showing"></span> of <span v-text="numFiltered"></span> <span v-text="resource"></span>
            </div>
            <div :class="paginationClass">
                <nav aria-label="Page navigation">
                    <ul v-show="numPages > 1" class="pagination">
                        <li>
                            <a href="#" :class="activePage > 1 ? '' : 'disabled'" aria-label="Previous" @click.prevent="prevPage">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <li v-for="page in numPages" :class="activePage == page ? 'active' : ''">
                            <a href="#" v-text="page" @click.prevent="switchPage(page)">1</a>
                        </li>
                        <li>
                            <a href="#" :class="activePage < numPages ? '' : 'disabled'" aria-label="Next" @click.prevent="nextPage">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div> 
		</div>
	</div>
</template>

<script>
	export default {
		props: {
			items: {
				type: Array | String,
				required: true,
			},
            typeMap: {
                type: Object,
                default: function () {
                    return {};
                },
            },
            form: {
                type: Object,
                default: function () {
                    return {};
                },
            },
            relationAttributes: {
                type: Object | Array,
                default: function () {
                    return {};
                },
            },
			columns: {
				type: Object,
				default: function () {
                    return {};
                },
			},
            displayColumns: {
                type: Array,
                default: function () {
                    return [];
                },
            },
            realTimeColumns: {
                type: Array,
                default: function () {
                    return [];
                },
            },
			resource: {
				type: String,
				required: true,
			},
            syncUrl: {
                type: String,
                default: '',
            },
			quickEdit: {
				type: Function,
				default: function () {
				},
			},
			permissions: {
				type: Object,
				default: function () {
                    return {};
                },
			},
			deleteFunc: {
				type: Function,
				default: function () {
				},
			},
			collectionType: {
                type: String,
                default: 'list',
            },
            heavy: {
                type: Boolean,
                default: false,
            },
            subtype: {
                type: String,
                default: 'default',
            },
            trType: {
                type: String,
                default: 'content',
            },
			buttons: {
				type: Array,
				default: function () {
					return [];
				},
			},
            perPageClass: {
                type: String,
                default: 'hidden',
            },
            perPageDefault: {
                type: String | Number,
                default: 10,
            },
            searchClass: {
                type: String,
                default: 'text-center',
            },
            statusClass: {
                type: String,
                default: 'hidden',
            },
            paginationClass: {
                type: String,
                default: '',
            },
		},

		data() {
			return {
				perPage: 10,
                perPageChoices: [5, 10, 20, 50, 100],
				activePage: 1,
				paginated: [],
                filtered: [],
				search: '',
				micromustache: micromustache,
                sortedItems: [],
                sortedBy: '',
                sortAsc: true,
                numFiltered: 0,
                parsedItems: [],
			};
		},

		watch: {
			search(newVal) {
                if (!this.heavy) {
                    this.filterSearch();
                    this.paginate();
                }

                if (this.heavy && newVal.length == 0) {
                    this.sync();
                }
			},

			items(newItems) {
                this.parseItems();
                if (!this.heavy) {
                    this.sortedItems = this.parsedItems;
                    this.filterSearch();
                    this.paginate();
                } else {
                    this.sync();
                }
			},

            perPage() {
                this.paginate();
            },

            activePage() {
                this.paginate();
            },

            numPages(newVal) {
                if (newVal < this.activePage && newVal > 0) {
                    this.activePage = newVal;
                }
            },

            heavy(newVal) {
                if (newVal) {
                    this.sync();
                    this.filterSearch = _.debounce(this.filterSearch, 700);
                }
            },

            filtered(newVal) {
                this.numFiltered = newVal.length;
            },
		},

		computed: {
			title() {
				return _.titleize(this.resource);
			},

            numPages() {
                return Math.ceil(this.numFiltered / this.perPage);
            },

            showing() {
                return this.numPages == this.activePage
                    ? this.numFiltered % this.perPage
                    : this.perPage;
            },
		},

        mounted() {
            this.parseItems();
            this.perPage = parseInt(this.perPageDefault);
            this.sortedItems = this.parsedItems;
            this.activePage = 1;

            if (this.heavy) {
                this.sync();
            } else {
                this.filterSearch();
                this.paginate();
            }
        },

		methods: {
            parseItems() {
                this.parsedItems = Array.isArray(this.items) ? this.items : JSON.parse(this.items);
            },

			titleize(str) {
				return _.titleize(str);
			},

            paginate() {
                if (this.heavy) {
                    this.sync();
                } else {
                    var start = (this.activePage - 1) * this.perPage;
                    this.paginated = this.filtered.slice(
                        start,
                        parseInt(start) + parseInt(this.perPage)
                    );
                }
            },

            growlError(error) {
                $.growl.error({
                    title: 'Error',
                    message: 'Something went wrong. Please check your internet connection and reload the page.'
                });
                console.log(error);
            },

            sync() {
                this.$http.get(url(this.syncUrl ? this.syncUrl : this.resource)
                        + '?skip=' + ((this.activePage - 1) * this.perPage)
                        + '&take=' + this.perPage
                        + '&orderBy=' + this.sortedBy
                        + '&orderDir=' + (this.sortAsc ? 'asc' : 'desc')
                        + '&search=' + (this.search)
                ).then((response) => {
                    this.paginated = response.body.items;
                    this.numFiltered = response.body.count;
                }, (error) => {
                    this.growlError(error);
                });
            },

            filterSearch() {
                if (!this.heavy) {
                    if (this.search) {
                        var lowercase = this.search.toLowerCase();
                        this.filtered = this.sortedItems.filter(
                            val => _.values(val).filter(
                                cell => (
                                    typeof cell == 'string'
                                    && cell.toLowerCase().includes(lowercase)
                                )
                            ).length
                        );
                    } else {
                        this.filtered = this.sortedItems;
                    }
                }
            },

            prevPage() {
                if (this.activePage > 1) {
                    --this.activePage;
                }

                e.preventDefault();
            },

            nextPage() {
                if (this.activePage < this.numPages) {
                    ++this.activePage;
                }
            },

            switchPage(page) {
                this.activePage = page;
            },

            sortBy(c) {
                if (this.sortedBy == c) {
                    this.sortAsc = !this.sortAsc;
                } else {
                    this.sortedBy = c;
                    this.sortAsc = true;
                }

                if (this.heavy) {
                    this.sync();
                } else {
                    this.sortedItems = _.orderBy(
                        this.parsedItems,
                        [c => c.name.toLowerCase()],
                        this.sortAsc ? 'asc' : 'desc'
                    );
                    this.filterSearch();
                    this.paginate();
                }
            },
		},
	};
</script>
