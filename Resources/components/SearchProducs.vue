<template>
    <div class="search-content">

        <auto-complete url-remote="common/items/autocomplete"
                       limit-search="1"
                       ac-prefix="query"
                       :ac-search="search"
                       :ac-placeholder="$lang.search"
                       ac-clear="true"
                       @select-item="productSelect">
        </auto-complete>
    </div>
</template>

<script>
    import AutoComplete from "./AutoComplete";

    export default {
        name: "SearchProducs",
        components: {AutoComplete},
        data: function () {
            return {
                search: '',
            }
        },
        methods: {
            productSelect: function (item) {
                this.$emit('product', item);

                this.isHidden = true;
                this.search = '';
            },
        }
    }
</script>

<style scoped>
</style>