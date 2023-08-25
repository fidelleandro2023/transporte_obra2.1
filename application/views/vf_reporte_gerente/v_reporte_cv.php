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
              <!-- <img src="https://randomuser.me/api/portraits/men/0.jpg"> -->
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
                                <td style="background:#E0D7F1;cursor:pointer" @click="detalleDataPOCVOnline(props.item.jefatura,props.item.empresaColabDesc,8 )">{{ props.item.pre_registro }}</td>
                                <td style="background:#E0D7F1;cursor:pointer" @click="detalleDataPOCVOnline(props.item.jefatura,props.item.empresaColabDesc,2 )">{{ props.item.diseno  }}</td>
                                <td style="background:#E0D7F1;cursor:pointer" @click="detalleDataPOCVOnline(props.item.jefatura,props.item.empresaColabDesc,3 )">{{ props.item.obra }} </td>
                                <td style="background:#E0D7F1;cursor:pointer" @click="detalleDataPOCVOnline(props.item.jefatura,props.item.empresaColabDesc,9 )">{{ props.item.hoy_pre_liqui }}</td>   
                            </template>
                    </v-data-table>
                  </v-container>
                </v-card>
            </div> 
            
            <div v-if="item.tabs=='busqueda'">
                <v-card>
                  <v-container
                            fluid
                            grid-list-md
                        >
                    <v-card-title class="headline grey lighten-2" primary-title>
                        Itemplan por Fecha de Construcción (CV)  
                    </v-card-title>
                        <v-text-field
                            v-model="searchDetalle"
                            append-icon="search"
                            label="Search"
                            single-line
                            hide-details
                        ></v-text-field>
                     <v-data-table
                        :headers = "headerRepJefEECC"
                        :items   = "arrayCVJefEECC"
                        :loading = "true"
                        class    = "elevation-1"
                        :search  = "searchDetalle">
                           <template slot="items" slot-scope="prop1">
                                <td>{{ prop1.item.jefatura }} </td>
                                <td>{{ prop1.item.eeccplanobra }}</td>
                                <td>{{ prop1.item.sin_fecha }}</td> 

    <td style="background:#E0D7F1;cursor:pointer" @click="detalleJefEECCCV(prop1.item.jefatura,prop1.item.eeccplanobra,1 )">{{ prop1.item.FECH_1 }}</td>
    <td style="background:#E0D7F1;cursor:pointer" @click="detalleJefEECCCV(prop1.item.jefatura,prop1.item.eeccplanobra,2 )">{{ prop1.item.FECH_2 }}</td>
    <td style="background:#E0D7F1;cursor:pointer" @click="detalleJefEECCCV(prop1.item.jefatura,prop1.item.eeccplanobra,3 )">{{ prop1.item.FECH_3 }}</td>
    <td style="background:#E0D7F1;cursor:pointer" @click="detalleJefEECCCV(prop1.item.jefatura,prop1.item.eeccplanobra,4 )">{{ prop1.item.FECH_4 }}</td>
    <td style="background:#E0D7F1;cursor:pointer" @click="detalleJefEECCCV(prop1.item.jefatura,prop1.item.eeccplanobra,5 )">{{ prop1.item.FECH_5 }}</td>
    <td style="background:#E0D7F1;cursor:pointer" @click="detalleJefEECCCV(prop1.item.jefatura,prop1.item.eeccplanobra,6 )">{{ prop1.item.FECH_6 }}</td>
    <td style="background:#E0D7F1;cursor:pointer" @click="detalleJefEECCCV(prop1.item.jefatura,prop1.item.eeccplanobra,7 )">{{ prop1.item.FECH_7 }}</td>
    <td style="background:#E0D7F1;cursor:pointer" @click="detalleJefEECCCV(prop1.item.jefatura,prop1.item.eeccplanobra,8 )">{{ prop1.item.FECH_8 }}</td>
    <td style="background:#E0D7F1;cursor:pointer" @click="detalleJefEECCCV(prop1.item.jefatura,prop1.item.eeccplanobra,9 )">{{ prop1.item.FECH_9 }}</td>
    <td style="background:#E0D7F1;cursor:pointer" @click="detalleJefEECCCV(prop1.item.jefatura,prop1.item.eeccplanobra,10 )">{{ prop1.item.FECH_10 }}</td>
    <td style="background:#E0D7F1;cursor:pointer" @click="detalleJefEECCCV(prop1.item.jefatura,prop1.item.eeccplanobra,11 )">{{ prop1.item.FECH_11 }}</td>
    <td style="background:#E0D7F1;cursor:pointer" @click="detalleJefEECCCV(prop1.item.jefatura,prop1.item.eeccplanobra,12 )">{{ prop1.item.FECH_12 }}</td> 

                                
                            </template>
                    </v-data-table>
                  </v-container>
                </v-card>
            </div> 

            </v-tab-item>
        </v-tabs-items>
      </v-flex>
    </v-layout>
    
    <v-dialog v-model="modalDetJefEECC" width="100%">
        <v-card>
          <v-card-title class="headline grey lighten-2" primary-title>
            Detalle Itemplan CV
          </v-card-title>
            <v-text-field
                v-model="searchDetalle"
                append-icon="search"
                label="Search"
                single-line
                hide-details
            ></v-text-field>
          <v-data-table
            :headers = "headerDetJefEECC"
            :items   = "arrayDetCVJefEECC"
            :loading = "true"
            class    = "elevation-1"
            :search  = "searchDetalle">
                <template slot="items" slot-scope="prop2">
                    <td>{{ prop2.item.itemplan }} </td>
                    <td>{{ prop2.item.nombreProyecto}}</td>
                    <td>{{ prop2.item.jefatura }}</td>
                    <td>{{ prop2.item.empresaColabDesc }}</td>
                    <td>{{ prop2.item.estadoPlanDesc }} </td>
                    <td>{{ prop2.item.fecha_creacion }} </td>
                    <td>{{ prop2.item.fec_termino_constru }} </td>
                    <td>{{ prop2.item.avance }} % </td>
                </template>
          </v-data-table>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="primary"
              flat
              @click="modalDetJefEECC = false"
            >
              Cerrar
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
      
      <v-dialog v-model="modalDetPOCV" width="100%">
        <v-card>
          <v-card-title class="headline grey lighten-2" primary-title>
            Detalle Itemplan CV
          </v-card-title>
            <v-text-field
                v-model="searchDetalle"
                append-icon="search"
                label="Search"
                single-line
                hide-details
            ></v-text-field>
          <v-data-table
            :headers = "headerDetJefEECC"
            :items   = "arrayDetPOCV"
            :loading = "true"
            class    = "elevation-1"
            :search  = "searchDetalle">
                <template slot="items" slot-scope="prop3">
                    <td>{{ prop3.item.itemplan }} </td>
                    <td>{{ prop3.item.nombreProyecto}}</td>
                    <td>{{ prop3.item.jefatura }}</td>
                    <td>{{ prop3.item.empresaColabDesc }}</td>
                    <td>{{ prop3.item.estadoPlanDesc }} </td>
                    <td>{{ prop3.item.fecha_creacion }} </td>
                    <td>{{ prop3.item.fec_termino_constru }} </td>
                    <td>{{ prop3.item.avance }} % </td>
                </template>
          </v-data-table>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="primary"
              flat
              @click="modalDetPOCV = false"
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
  <script src="<?php echo base_url();?>public/js/js_reporte_gerente/reporte_cv.js?v=<?php echo time();?>"></script>
  <script src="<?php echo base_url(); ?>public/js/Utils.js?v=<?php echo time() ?>"></script>
</body>
</html>
