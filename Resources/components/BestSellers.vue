<template>
    <div>
        <label>{{ $lang.top_products }}</label>

        <a href="javascript:;"
           v-on:click="addCar(p)"
           v-for="p in products"
           v-bind:key="p.id"
           class="btn btn-default btn-produto-mais">
            <div class="row">
                <div class="col-md-10 col-xs-10 text-left ng-binding">
                    {{ p.name }}
                </div>

                <div class="col-md-2 col-xs-2 text-text">
                    <i class="fa fa-plus"></i>
                </div>
            </div>
        </a>
    </div>
</template>

<script>
    export default {
        name: "BestSellers",
        data: function(){
          return {
              products: []
          }
        },
        methods: {
          addCar(item){
              this.$emit('product', item);
          }
        },
        mounted: function () {
            var self = this;

            $.ajax({
                url: 'pos/best-sellers',
                type: 'GET',
                dataType: 'JSON',
                data: {},
                success: function (data) {
                    self.products = data;
                }
            });
        }
    }
</script>

<style scoped>
    .btn-produto-mais {
        display: block;
        height: 60px;
        line-height: 40px;
        margin-top: 10px;
    }

    .btn-produto-mais i {
        font-size: 17px;
        line-height: 45px;
    }
</style>