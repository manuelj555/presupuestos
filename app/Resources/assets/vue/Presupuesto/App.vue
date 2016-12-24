<template>
    <div id="presupuesto" class="overflow" style="height:auto;">
        <div class="row presupuesto-headers">
            <div class="col-xs-2">Título</div>
            <div class="col-xs-10">
                <input type="text" v-model="titulo" class="form-control" />
            </div>
        </div>

        <div class="row presupuesto-headers">
            <div class="col-xs-1">Orden</div>
            <div class="col-xs-4 col-lg-6 text-center">Mano de Obra</div>
            <div class="col-xs-2 col-lg-1 text-center">Bs</div>
            <div class="col-xs-2">Cantidad</div>
            <div class="col-xs-2 col-lg-1 text-center">Total Bs</div>
            <div class="col-xs-1">Remover</div>
        </div>

        <Draggable :list="items" :options="{handle: '.posicion', animation: 150}">
            <transition-group>
                <Descripcion v-for="(item, index) in items"
                            :descripcion="item"
                            :key="index"
                            :index="index"
                            @change="changeItemData"
                            @remove="removeItem"
                />
            </transition-group>
        </Draggable>

        <div class="row prespuesto-headers">
            <div class="col-xs-7">
                <a class="btn btn-default" @click="addItem">+ Linea</a>
            </div>
            <div class="col-xs-2">TOTAL :</div>
            <div class="col-xs-2 text-right">{{ total }} Bs</div>
            <div class="col-xs-1"></div>
        </div>
        <!--<tfoot>-->
        <!--<tr>-->
        <!--<td colspan="2">-->
        <!--{% if presupuesto.id is not null %}-->
        <!--<a href="{{ path(" presupuesto_export",{id : presupuesto.id}) }}" target="_blank"-->
        <!--class="btn">Imprimir</a>-->
        <!--{% else %}-->
        <!--<a href="#" target="_blank" class="btn disabled">Imprimir</a>-->
        <!--{% endif %}-->
        <!--<input class="btn pull-right guardar" value="Guardar" type="submit"/>-->
        <!--</td>-->
        <!--<td colspan="2" style="text-align: right;padding-right: 20px;vertical-align: middle">-->
        <!--<b>TOTAL :</b>-->
        <!--</td>-->
        <!--<td colspan="2" style="text-align: center;font-size: 18px;vertical-align: middle"-->
        <!--class="total">-->
        <!--{{ total }} Bs-->
        <!--</td>-->
        <!--</tr>-->
        <!--</tfoot>-->
    </div>
</template>

<script>
    import Vue from 'vue'
    import Draggable from 'vuedraggable'
    import Descripcion from 'Presupuesto/Descripcion.vue'
    import _ from 'lodash'
    import axios from 'axios'

    export default {
        props: {
            apiUrl: {required: true, type: String},
        },

        data () {
            return {
                titulo: null,
                items: [],
            }
        },

        created () {
//            this.resource = this.$resource(this.apiUrl + '{/id}')
            this.getManosDeObra()
        },

        computed: {
            total () {
                return _.sumBy(this.items, 'total')
            }
        },

        methods: {
            getManosDeObra () {
                axios.get(this.apiUrl).then(({data}) => {
                    this.id = data.id
                    this.titulo = data.titulo || ''

                    if(data.descripciones){
                        this.items = data.descripciones
                    }
                })
            },
            save () {
                axios.post(this.apiUrl, {
                    titulo: this.titulo,
                    descripciones: this.items,
                }).then((response) => {
                    console.debug(response)
                })
            },
            addItem () {
                this.items.push({
                    id: null,
                    posicion: null,
                    descripcion: null,
                    precio: null,
                    cantidad: null,
                    subtotal: null,
                })
                this.save()
            },
            changeItemData(index, data) {
                Vue.set(this.items, index, Object.assign({}, this.items[index], data))
                console.log('Actualizando', index, data, this.items[index])
                this.save()
            },
            removeItem(index) {
                let items = _.clone(this.items)
                console.log(index)
                items.splice(index, 1)

                this.items = items

                this.save()
            },
        },

        components: {Descripcion, Draggable},
    }
</script>