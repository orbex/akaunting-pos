<template>
    <div class="modal fade"
         id="checkoutModal"
         tabindex="-1"
         role="dialog"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">
                        {{ $lang.receiver_account }}
                    </h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group"
                                 ng-class="{'has-error': error.forma_pagamento}">
                                <label class="font-weight-normal">
                                    {{ $lang.payment_method }}
                                </label>

                                <select v-model="form.forma_pagamento"
                                        class="form-control">
                                    <option value="1" selected="selected">Money</option>
                                    <option value="2">Card</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group"
                                 ng-class="{'has-error': error.condicao_pagamento}">
                                <label class="font-weight-normal">
                                    {{ $lang.number }}
                                </label>

                                <input class="form-control" v-model="form.order_number">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="row" style="line-height: 40px;">
                                <div class="col-md-6">
                                    {{ $lang.total_payable }}
                                </div>
                                <div class="col-md-6 text-right">
                                    <span class="info" style="color: #00c0ef">
                                        {{ dataForm.amount | toCurrency }}
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">
                        {{ $lang.cancel }}
                    </button>

                    <button type="button"
                            @click="save()"
                            class="btn btn-success">
                        {{ $lang.checkout }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Checkout",
        data() {
            return {
                form: {
                    condicao_pagamento: 0,
                    forma_pagamento: '1'
                },
                error: {},
                currencies: []
            }
        },
        props: [
            'visible',
            'data-form'
        ],
        watch: {
            visible: function (newVal, oldVal) {
                if (newVal)
                    $('#checkoutModal').modal();
            }
        },
        methods: {
            closeDialog() {
                this.$emit('update:visible', false);
                $('#checkoutModal').modal('hide');
            },
            save(){
                var self = this;

                $.ajax({
                    url: 'pos/sale',
                    type: 'POST',
                    data: this.dataForm,
                    success: function (data) {
                        self.$emit('checkout-form', data);
                        self.closeDialog();
                    }
                });
            }
        },
        mounted() {
            var self = this;

            $('#checkoutModal').on('hidden.bs.modal', function () {
                self.$emit('update:visible', false);
            });

            $.ajax({
                url: 'pos/currencies',
                type: 'GET',
                dataType: 'JSON',
                success: function (data) {
                    self.currencies = data;
                }
            });
        },
    }
</script>

<style scoped>
</style>