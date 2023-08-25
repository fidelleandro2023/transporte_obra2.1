<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
  <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/vuetify/dist/vuetify.min.css" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
</head>
<body>
<div id="app">
  <v-app id="inspire">
    <v-layout column>
      <v-flex>
        <v-toolbar color="indigo" dark>
          <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
          <v-toolbar-title>Reporte</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn icon>
            <v-icon>search</v-icon>
          </v-btn>

                <v-tabs
                slot    = "extension"
                v-model = "currentItem"
                color   = "transparent"
                fixed-tabs
                slider-color="yellow"
                >
                <v-tab
                    v-for="item in items"
                    
                    :href="'#tab-' + item.tabs"
                    :key="item.tabs"        
                >
                    {{ item.tabs }}
                </v-tab>
            </v-menu>
            </v-tabs>  
        </v-toolbar>


    <v-navigation-drawer
        v-model="drawer"
        :mini-variant="mini"
        absolute
        dark
        temporary
      >
        <v-list class="pa-1">
          <v-list-tile v-if="mini" @click.stop="mini = !mini">
            <v-list-tile-action>
              <v-icon>chevron_right</v-icon>
            </v-list-tile-action>
          </v-list-tile>
  
          <v-list-tile avatar tag="div">
            <v-list-tile-avatar>
              <!-- <img src="https://randomuser.me/api/portraits/men/85.jpg"> -->
            </v-list-tile-avatar>
  
            <v-list-tile-content>
              <!-- <v-list-tile-title>John Leider</v-list-tile-title> -->
            </v-list-tile-content>
  
            <v-list-tile-action>
              <v-btn icon @click.stop="mini = !mini">
                <v-icon>chevron_left</v-icon>
              </v-btn>
            </v-list-tile-action>
          </v-list-tile>
        </v-list>
  
        <v-list class="pt-0" dense>
          <v-divider light></v-divider>
  
          <v-list-tile
            v-for="itemsMenu in itemsMenu"
            :key="itemsMenu.title"
            v-bind:onclick="itemsMenu.metodo"
          >
            <v-list-tile-action>
              <v-icon>{{ itemsMenu.icon }}</v-icon>
            </v-list-tile-action>
  
            <v-list-tile-content>
              <v-list-tile-title>{{ itemsMenu.title }}</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-list>
      </v-navigation-drawer>

        <v-tabs-items v-model="currentItem">
            <v-tab-item
            v-for="item in items"
            :id="'tab-' + item.tabs"
            :key="item.tabs"
            >
            <v-card flat>
                <v-card-text>
                <div v-if="item.tabs=='Avance del día'">
                    <h2>Reporte a nivel Zonal</h2>
                            <v-card>
                                <v-container
                                    fluid
                                    grid-list-md
                                >
                                <v-layout row wrap>
                                <v-flex >
                         
                                        <v-container fill-height fluid pa-2>
                                        <v-layout fill-height>
                                            <v-flex xs12 align-end flexbox>
                                                    <v-card-title>
                                                        DESDE HACE 3 D&Iacute;AS A HOY
                                                        <v-spacer></v-spacer>
                                                        <v-text-field
                                                            v-model="search"
                                                            append-icon="search"
                                                            label="Search"
                                                            single-line
                                                            hide-details
                                                        ></v-text-field>
                                                    </v-card-title>
                                                    <v-data-table
                                                        :headers = "headers"
                                                        :items   = "arrayJefatura"
                                                        :loading = "true"
                                                        class   = "elevation-1"
                                                        :search  = "search"
                                                        id     = "tbZonal"
                                                    >
                                                    <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
                                                        <template slot="items" slot-scope="props">
                                                            <td>{{ props.item.zona }}</td>
                                                            <td>{{ props.item.cantidadAntesAyer }}</td>
                                                            <td>{{ props.item.cantidadAyer }}</td>
                                                            <td>{{ props.item.cantidadHoy }}</td>
                                                        </template>

                                                    <v-alert slot="no-results" :value="true" color="error" icon="warning">
                                                        No se encontro "{{ search }}".
                                                    </v-alert>
                                                </v-data-table>
                                            </v-flex>
                                        </v-layout>
                                </v-container>
                      

                            
                        </v-flex>
                        </v-layout>
                    </v-container>
                    </v-card>



                </div>
                <div v-if="item.tabs=='evolutivo'">
                <h2>Reporte a nivel Emp. Colaboradora</h2>
                    <v-card>
                        <v-container
                            fluid
                            grid-list-md
                        >
                        <v-layout row wrap>
                        <v-flex >
                     
                            <v-container fill-height fluid pa-2>
                                <v-layout fill-height>
                                    <v-flex xs12 align-end flexbox>
                                            <v-card-title>
                                                DESDE HACE 3 D&Iacute;AS A HOY
                                                <v-spacer></v-spacer>
                                                <v-text-field
                                                    v-model="search"
                                                    append-icon="search"
                                                    label="Search"
                                                    single-line
                                                    hide-details
                                                ></v-text-field>
                                            </v-card-title>
                                            <v-data-table
                                                :headers = "headersEecc"
                                                :items   = "arrayEecc"
                                                :loading = "true"
                                                class   = "elevation-1"
                                                :search  = "search"
                                            >
                                            <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
                                                <template slot="items" slot-scope="props">
                                                    <td>{{ props.item.empresaColabDesc }}</td>
                           
                                                           <td style="background:#E0D7F1;cursor:pointer" @click="datalleEmpresaColab(props.item.idEmpresaColab, 1)">{{ props.item.hoyLima }}</td>
                                                    <td style="background:#E0D7F1;cursor:pointer" @click="datalleEmpresaColab(props.item.idEmpresaColab, 2)">{{ props.item.hoyProvincia }}</td>
                                                </template>

                                                    <v-alert slot="no-results" :value="true" color="error" icon="warning">
                                                        No se encontro "{{ search }}".
                                                    </v-alert>
                                                </v-data-table>
                                            </v-flex>
                                        </v-layout>
                                </v-container>
                                                      
                        </v-flex>
                        </v-layout>
                    </v-container>
                    </v-card>

                </div>
                <div v-if="item.tabs=='Gráficos'">
                    <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                </div>
                </v-card-text>
            </v-card>
            <div v-if="item.tabs=='status'">
                <v-card>
                    <v-container
                            fluid
                            grid-list-md
                        >
                        <v-card-title class="headline grey lighten-2" primary-title>
                            Detalle
                        </v-card-title>
                            <v-text-field
                                v-model="searchDetalle"
                                append-icon="search"
                                label="Search"
                                single-line
                                hide-details
                            ></v-text-field>
                        <v-data-table
                            :headers = "headerDetalleObra"
                            :items   = "arrayTodoDetalle"
                            :loading = "true"
                            class    = "elevation-1"
                            :search  = "searchDetalle">
                                <template slot="items" slot-scope="props">
                                    <td>{{ props.item.jefatura }} </td>
                                    <td>{{ props.item.empresaColabDesc }}</td>
                                    <td>{{ props.item.diseno  }}</td>
                                    <td>{{ props.item.obra }}     </td>
                                    <td>{{ props.item.hoy_pre_liqui }}</td>
                                    <td>{{ props.item.hoy_trunco }}</td>
                                    <td>{{ props.item.hoyParalizacion }}</td>
                                </template>
                        </v-data-table>
                    </v-container>
                </v-card>
            </div>
            </v-tab-item>
        </v-tabs-items>
      </v-flex>
    </v-layout>
    
    <v-dialog v-model="modalDetalle" width="800">
        <v-card>
          <v-card-title class="headline grey lighten-2" primary-title>
            Detalle
          </v-card-title>
            <v-text-field
                v-model="searchDetalle"
                append-icon="search"
                label="Search"
                single-line
                hide-details
            ></v-text-field>
          <v-data-table
            :headers = "headerDetalle"
            :items   = "arrayDetalleEmpresaColab"
            :loading = "true"
            class    = "elevation-1"
            :search  = "searchDetalle">
                <template slot="items" slot-scope="props">
                    <td>{{ props.item.itemPlan }} </td>
                    <td>{{ props.item.nombreProyecto}}</td>
                    <td>{{ props.item.zonalDesc }}</td>
                    <td>{{ props.item.indicador }}</td>
                    <td>{{ props.item.hora }}     </td>
                </template>
          </v-data-table>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="primary"
              flat
              @click="modalDetalle = false"
            >
              Cerrar
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
  </v-app>
</div>
  <script src="<?php echo base_url(); ?>public/bower_components/jquery/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vuetify/dist/vuetify.js"></script>
  <script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
  <script src="<?php echo base_url();?>public/js/js_reporte_gerente/reporte_sinfix.js?v=<?php echo time();?>"></script>
  <script src="<?php echo base_url(); ?>public/js/Utils.js?v=<?php echo time() ?>"></script>
</body>
</html>