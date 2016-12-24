<template>
    <div class="descripcion-presupuesto row">
        <div class="col-xs-1 posicion">
            <i class="fa fa-arrows" ng-click="subir(desc)"></i>
        </div>
        <div class="col-xs-4 col-lg-6">
            <input type="text"
                   v-model="data.descripcion"
                   class="descripcion form-control"
                   @change="changeData"
            />
        </div>
        <div class="col-xs-2 col-lg-1">
            <input
                    type="text"
                    v-model="data.precio"
                    class="precio form-control"
                    @change="changeData"
            />
        </div>
        <div class="col-xs-2">
            <input type="text" v-model="data.cantidad" class="cantidad form-control" @change="changeData"/>
        </div>
        <div class="col-xs-2 col-lg-1 sub-total text-right">{{ data.total }} Bs</div>
        <div class="col-xs-1 opciones">
            <i class="fa fa-remove" @dblclick="remove"></i>
        </div>
    </div>
</template>

<script>
    import _ from 'lodash'
    import $ from 'jquery'

    //    const DIRECTION_DOWN = 'down'
    //    const DIRECTION_UP = 'up'

    export default {
        props: {
            index: {required: true, type: Number},
            item: {required: true, type: Object},
        },

        data () {
            return {
                data: _.clone(this.item),
            }
        },

        updated() {
            console.debug('Actualizado componente de item', this.item)
        },

        computed: {
            data () {
                return _.clone(this.item)
            },
        },

        methods: {
            changeData () {
                this.data.total = this.getTotal()
                this.$emit('change', this.index, this.data)
            },
            getTotal () {
                return _.toString(this.data.precio).replace(/\D+/g, '')
                        * _.toString(this.data.cantidad).replace(/\D+/g, '')
            },
            remove(){
                console.log(this.index)
                this.$emit('remove', this.index)
            },
        },
    }
</script>