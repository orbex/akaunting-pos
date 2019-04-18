<template>
    <div>
        <input :placeholder="acPlaceholder" class="form-control"
                v-model="search"
                autocomplete="off"
                v-on:blur="blurEvent()"
                v-on:keyup="getSearch()"/>

        <div class="list-ac">
            <ul v-show="!this.isHidden">
                <li v-show="isSearch">
                    <a class="searching" href="javascript:;">
                        Pesquisando...
                    </a>
                </li>

                <li v-show="!isSearch && data.length <= 0">
                    <a class="searching" href="javascript:;">
                        Nenhum cliente encontrado
                    </a>
                </li>

                <li v-show="!isSearch && data.length > 0" v-bind:key="r.id" v-for="r in data">
                    <a href="javascript:;" v-on:click="selectSearch(r)">
                        {{ r.name }}
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        name: "AutoComplete",
        props: [
            'url-remote',
            'limit-search',
            'ac-search',
            'ac-prefix',
            'ac-clear',
            'ac-placeholder'
        ],
        data(){
            return{
                search: '',
                data: [],
                isSearch: false,
                isHidden: true
            }
        },
        methods: {
            selectSearch(r){
                this.isHidden = true;
                this.search = r.name;
                this.$emit('select-item', r);

                if(this.acClear)
                    this.search = '';
            },
            getSearch: function () {
                this.$emit('update:acSearch', this.search);

                var self = this;
                this.isSearch = true;
                this.isHidden = false;

                $.ajax({
                    url: this.urlRemote,
                    type: 'GET',
                    dataType: 'JSON',
                    data: `${this.acPrefix}=${this.search}`,
                    success: function (data) {
                        self.data = data;
                        self.isSearch = false;
                    }
                });
            },
            blurEvent: function () {
                setTimeout(() => this.isHidden = true, 100);
            }  
        },
    }
</script>

<style scoped>
 .search-content{
        position: relative;
    }

    .list-ac{
        position: absolute;
        z-index: 99;
        width: 100%;
    }

    .list-ac ul {
        padding: 0px;
        margin: 0px;
        border: solid 1px #f9f9f9;
    }

    .list-ac ul li {
        list-style: none;
    }

    .list-ac ul li a {
        display: block;
        padding: 15px 20px;
        background: #fff;
        color: #666;
        font-size: 15px;
    }

    .list-ac ul li:nth-child(odd) > a {
        background: #f9f9f9;
    }

    .list-ac ul li a:hover {
        background: #79B1C3;
        color: #fff;
    }

    .searching, .searching:hover{
        cursor: default;
        background: #fff !important;
        color: #666 !important;
    }
</style>
