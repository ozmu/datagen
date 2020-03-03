<template>
    <div>
        <div class="card">
            <div class="card-body">
                <div class="overlay" v-if="loading">
                    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                </div>
                <div class="row">
                    <div class="table-full">
                        <button class="btn btn-primary create-btn" @click="create.modal = true">
                            <b-icon icon="plus-circle"></b-icon>
                        </button>
                        <input v-model="search" type="text" class="form-control search-text" placeholder="Search">
                        <b-table
                        :data="filteredUsers"
                        :paginated="true"
                        :per-page="10"
                        width="100%"
                        :striped="true"
                        :pagination-simple="false"
                        sort-icon="arrow-up">
                            <template slot-scope="props">
                                <b-table-column field="id" label="ID" width="5%" sortable>
                                    {{ props.row.id }}
                                </b-table-column>
                                
                                <b-table-column field="name" label="Name" width="25%" sortable>
                                    {{ props.row.name }}
                                </b-table-column>
                                
                                <b-table-column field="email" label="Email" width="25%" sortable>
                                    {{ props.row.email }}
                                </b-table-column>
                                
                                <b-table-column field="balance" label="Balance (TL)" width="10%" sortable>
                                    <b-tag type="is-info">
                                        {{ props.row.balance }}
                                        <b-icon icon="currency-try" custom-size="mdi-14px"></b-icon>
                                    </b-tag>
                                </b-table-column>
                                
                                <b-table-column field="text_count" label="Etiketlenen Metin" width="10%" sortable>
                                    <b-tag type="is-success" :class="{'text-count': props.row.text_count != 0}" @click.native="getTexts(props.row)">
                                        {{ props.row.text_count }}
                                    </b-tag>
                                </b-table-column>
                                
                                <b-table-column field="is_admin" label="Yetki" width="10%" sortable>
                                    <b-tag v-if="props.row.is_admin" type="is-danger">Admin</b-tag>
                                    <b-tag v-else type="is-warning">Kullanıcı</b-tag>
                                </b-table-column>
                                
                                <b-table-column field="is_deleted" label="Durum" width="5%" sortable>
                                    <b-tag v-if="props.row.is_deleted" type="is-danger">Silindi</b-tag>
                                    <b-tag v-else type="is-success">Kayıtlı</b-tag>
                                </b-table-column>
                                
                                <b-table-column label="İşlemler" width="15%">
                                    <b-tag type="is-warning" @click.native="editUser(props.row)" title="Edit" class="action">
                                        <b-icon icon="file-edit-outline" custom-size="mdi-18px"></b-icon>
                                    </b-tag>
                                    <b-tag type="is-danger" @click.native="destroyUser(props.row)" title="Delete" class="action">
                                        <b-icon icon="close-circle" custom-size="mdi-18px"></b-icon>
                                    </b-tag>
                                </b-table-column>
                            </template>
                        </b-table>
                    </div>
                </div>
            </div>
        </div>

        <!-- BEGIN::Create Modal -->
        <b-modal :active.sync="create.modal" :width="640" scroll="keep">
            <div class="card-content">
                <div class="content">
                    <div class="form-group">
                        <label>İsim</label>
                        <input type="text" class="form-control" v-model="create.data.name">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" v-model="create.data.email">
                    </div>
                    <div class="form-group">
                        <label>Parola</label>
                        <input type="password" class="form-control" v-model="create.data.password">
                    </div>
                    <button class="btn btn-primary" @click="store">Kaydet</button>
                </div>
            </div>
        </b-modal>
        <!-- END::Create Modal -->

        <!-- BEGIN::Edit Modal -->
        <b-modal :active.sync="edit.modal" :width="640" scroll="keep">
            <div class="card-content">
                <div class="content">
                    <div class="form-group">
                        <label>İsim</label>
                        <input type="text" class="form-control" v-model="edit.data.name">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" v-model="edit.data.email">
                    </div>
                    <button class="btn btn-primary" @click="update">Kaydet</button>
                </div>
            </div>
        </b-modal>
        <!-- END::Edit Modal -->

        <!-- BEGIN::Texts Modal -->
        <b-modal custom-class="texts-modal-content" :active.sync="texts.modal" :width="640" :height="540" scroll="keep" :onCancel="cancelTextsModal">
            <div class="card-content" :class="{'details-content': texts.details.open}">
                <div v-if="!texts.details.open" class="content">
                    <div class="overlay" v-if="texts.loading">
                        <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                    </div>
                    <input v-if="texts.data.length" v-model="filterTexts" type="text" class="form-control filter-text" placeholder="Filtrele">
                    <b-table
                    v-if="texts.data.length"
                    :data="filteredTexts"
                    :paginated="true"
                    :per-page="5"
                    width="100%"
                    :striped="true"
                    :pagination-simple="false"
                    sort-icon="arrow-up">
                        <template slot-scope="props">
                            <b-table-column field="id" label="ID" width="5%" sortable>
                                {{ props.row.id }}
                            </b-table-column>
                            
                            <b-table-column field="tagged_text" label="Etiketlenmiş" width="70%" sortable>
                                {{ props.row.tagged_text | truncate(100) }}
                            </b-table-column>
                            
                            <b-table-column label="İşlemler" width="25%">
                                <b-tag type="is-warning" @click.native="showText(props.row)" title="Metni Göster" class="action">
                                    <b-icon icon="eye" custom-size="mdi-18px"></b-icon>
                                </b-tag>
                                <b-tag type="is-danger" @click.native="destroyText(props.row)" title="Metni Sil" class="action">
                                    <b-icon icon="close-circle" custom-size="mdi-18px"></b-icon>
                                </b-tag>
                            </b-table-column>
                        </template>
                    </b-table>
                </div>
                <div v-else class="content details-height scrollbar">
                    <b-icon icon="arrow-left-circle" class="back-btn" @click.native="backFromDetails"></b-icon>
                    <div class="scrollbar">
                        <p class="tagged-text" v-html="taggedTextReplace(texts.details.data.tagged_text)"></p>
                    </div>
                </div>
            </div>
        </b-modal>
        <!-- END::Texts Modal -->
    </div>
</template>

<script>
export default {
    data(){
        return {
            loading: true,
            search: '',
            filterTexts: '',
            entities: [],
            create: {
                modal: false,
                data: {}
            },
            edit: {
                modal: false,
                data: {}
            },
            texts: {
                modal: false,
                loading: true,
                details: {
                    open: false,
                    data: {}
                },
                data: []
            },
            users: []
        }
    },

    computed: {
        filteredUsers(){
            if (this.search){
                return this.users.filter(user => user.name.toLowerCase().includes(this.search.toLowerCase()) || user.email.toLowerCase().includes(this.search.toLowerCase()))
            }
            return this.users
        },

        filteredTexts(){
            if (this.filterTexts){
                return this.texts.data.filter(text => text.id === this.filterTexts || text.tagged_text.toLowerCase().includes(this.filterTexts.toLowerCase()))
            }
            return this.texts.data
        }
    },

    mounted(){
        axios.get('/data/admin/users').then(response => {
            this.users = response.data
            this.loading = false
        })
        
        // Get Entities
        axios.get('/data/utils/entities').then(response => {
            this.entities = response.data
        })
    },

    methods: {
        getTexts(user){
            if (user.text_count){
                this.texts.modal = true
                axios.get('/data/admin/users/' + user.id + '?scope=texts').then(response => {
                    if (response.status === 200){
                        this.texts.loading = false
                        this.texts.data = response.data
                    }
                })
            }
        },

        showText(text){
            this.texts.details.data = text
            this.texts.details.open = true
        },

        destroyText(text){
            this.$buefy.dialog.confirm({
                message: 'Continue on this task?',
                onConfirm: () => {
                    axios.delete('/data/admin/users/' + text.user_id + '?scope=texts&text_user_id=' + text.id).then(response => {
                        if (response.data.status === 200){
                            this.texts.data.splice(this.texts.data.indexOf(text), 1)
                            this.$buefy.toast.open('Text deleted!')
                        }
                        else {
                            console.log('error while removing item.')
                        }
                    })
                }
            })
        },

        backFromDetails(){
            this.texts.details.data = {}
            this.texts.details.open = false
        },

        cancelTextsModal(){
            this.texts.data = []
            this.texts.loading = true
        },

        taggedTextReplace(taggedText){
            var pattern = new RegExp('<START:(.+?)>(.+?)<END>', 'gi')
            var match;
            do {
                match = pattern.exec(taggedText)
                if (match){
                    var e = this.entities.filter(entity => entity.entity.toLowerCase() === match[1].toLowerCase())
                    var entity = e.length ? e[0] : {color: '#000'}
                    taggedText = taggedText.replace('<START:' + match[1] + '>' + match[2] + '<END>', '<span class="selected" title="' + 
                    match[1] + '" style="background-color: ' + entity.color + '">' + match[2] + '</span>')
                }
            } while (match)
            return taggedText
        },

        store(){
            axios.post('/data/admin/users', this.create.data).then(response => {
                if (response.status === 201){
                    axios.get('/data/admin/users/' + response.data.id).then(response => {
                        this.users.push(response.data)
                    })
                }
                else {
                    console.log('There is an error!')
                }
                this.$buefy.snackbar.open({
                    message: response.status === 201 ? "User successfully created!" : JSON.stringify(response.data),
                    type: response.status === 201 ? 'is-success' : 'is-warning',
                    position: 'is-top',
                    actionText: response.status === 201 ? 'OK' : 'Retry',
                })
                if (response.status === 201){
                    this.create.modal = false;
                }
                this.create.modal = false;
            }).catch(e => {
                this.$buefy.snackbar.open({
                    message: e.response.data.message,
                    type: 'is-danger',
                    position: 'is-top',
                    actionText: 'OK'
                })
                console.log(e.response)
            })
        },

        editUser(user){
            this.edit.modal = true
            axios.get('/data/admin/users/' + user.id).then(response => {
                this.edit.data = response.data
            })
        },

        update(){
            axios.put('/data/admin/users/' + this.edit.data.id, this.edit.data).then(response => {
                this.edit.modal = false;
                this.$buefy.snackbar.open({
                    message: response.data,
                    type: response.status === 200 ? 'is-success' : 'is-warning',
                    position: 'is-top',
                    actionText: response.status === 200 ? 'OK' : 'Retry'
                })
                if (response.status === 200){
                    axios.get('/data/admin/users/' + this.edit.data.id).then(response => {
                        if (response.status === 200){
                            var objIndex = this.users.findIndex(obj => obj.id === response.data.id)
                            this.users[objIndex].name = response.data.name
                            this.users[objIndex].email = response.data.email
                            this.users[objIndex].balance = response.data.balance
                        }
                    })
                    this.edit.modal = false;
                }
            }).catch(e => {
                console.log(e.response)
            })
        },

        destroyUser(user){
            this.$buefy.dialog.confirm({
                message: 'Continue on this task?',
                onConfirm: () => {
                    axios.delete('/data/admin/users/' + user.id).then(response => {
                        if (response.data === 1){
                            this.users.splice(this.users.indexOf(user), 1)
                            this.$buefy.toast.open('User deleted!')
                        }
                    })
                }
            })
        }
    },

    filters: {
        truncate: function(value, limit){
            if (value.length > limit) {
                value = value.substring(0, (limit - 3)) + '...';
            }
            return value
        }
    }
}
</script>

<style scoped>
.create-btn, .search-text {
    float: right;
}
.search-text {
    width: 200px;
    margin-right: 10px;
    padding: 20px;
}
span.tag.is-info {
    width: 50px;
}
.text-count {
    cursor: pointer;
}
input.filter-text {
    width: 150px;
    padding: 10px;
    display: block;
    margin: 0 auto;
    margin-bottom: 20px;
}
.back-btn {
    position: absolute;
    top: 5px;
    left: 5px;
    cursor: pointer;
    transition: all ease .4s;
}
.back-btn:hover {
    color: #1976D2;
    transition: all ease .4s;
}
.content.details-height {
    height: 100%;
    text-align: justify;
    overflow-y: auto;
}
.details-content {
    height: 100%;
    overflow-y: hidden;
}
p.tagged-text {
    line-height: 22px;
}
</style>