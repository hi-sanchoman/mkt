<template>
  <div class="flex flex-col h-full">
  	<!-- верхняя панель-->
  	<div class="mb-8 flex items-center">
    	<h1 class="font-bold text-2xl w-2/12" @click="$page.props.auth.sidebar = true">Отчеты</h1>
		<div class="w-10/12 flex justify-between">
    	
    	<div class="w-64 flex justify-start">
    </div>

    <div class="flex space-x-3">
    	<div class="font-medium text-black pt-2" >Период</div>
      	<select class="rounded-full text-white h-8 px-6 text-sm bg-indigo-500 login_button" @change="getDealsByDate($event)" v-model="period">
          <option value="0">Сегодня</option>
          <option value="3">Неделя</option>
          <option value="2">Месяц</option>
          <option value="1">Год</option>
        </select>
    </div>
	<div class=" mr-4 ml-4 flex gap-4 items-center">
		<div class="font-medium text-black">По сотрудникам</div>
		<v-select placeholder="tets" class="border-b-2 w-80 pb-1 rounded-full text-white h-8 px-6 text-sm bg-gray-200 " :options="users" v-model="selectedUser" ref="user_id"></v-select>
    </div>

	  
    </div>
    	<div v-if="$page.props.auth.user.message"><img class="h-10" src="img/message2.png" @click="$page.props.auth.sidebar = true">
    		
    	</div>
    	<div v-else><img class="h-10" src="img/message.png" @click="$page.props.auth.sidebar = true">
    		
    	</div>
	</div>

	<!--панель офиса-->
	<div class="flex gap-6 flex-auto overflow-y-hidden">
	
		<!-- список документов-->
		<div class="w-full bg-white rounded-2xl  h-auto p-6 overflow-y-auto shadow-sm flex flex-col">
			
			<div class="border-1 rounded-lg mb-5 flex flex-col h-auto" v-if="show == 1">
					

				<div class="text-black font-medium mb-4">Планы на месяц</div>

				<div class="flex flex-wrap">


					<div class="w-6 md:w-1/2 xl:w-full p-0 flex flex-col gap-4 mb-5 pr-3">
						<div class="w-full md:w-1/2 xl:w-full p-0">
						<!--Metric Card-->
						<div class="bg-white border rounded shadow-ss p-4" @click="showSum">
							<div class="flex flex-row items-center">
								<div class="flex-shrink pr-4">
									<div class="rounded p-3 bg-green-600"><i class="fa fa-wallet fa-2x fa-fw fa-inverse"></i></div>
								</div>
								<div>
									<h5 class="font-bold uppercase text-gray-500">Сумма сделок</h5>
								</div>
								<div class="flex-1 text-right md:text-center">
									
									<h3 class="font-bold text-xl">{{this.total}} из {{ this.sum }}</h3>
								</div>
								<div>
									<div class="text-3xl font-bold text-indigo-500">
										{{ parseFloat(this.sum / 5000000 * 100).toFixed(0) }}%
									</div>
								</div>
							</div>
						</div>
						<div v-if="showDeals" class="mt-5">
							<table>
								<tr>
									<th class="px-6 pt-4 pb-4">Сделка</th>
									<th>Прогресс</th>
									<th>Этап</th>
									<th>Сумма</th>
								</tr>
								<tr v-for="deal in sdelki">
									<td class="px-6 pt-3 pb-3 w-2/12"><b>{{deal.name}}</b></td>
									<td class="w-6/12"><div  class="bg-green-500 h-5 text-right" :style="'width: ' + (deal.progress / 18 * 100) +'%'"><p class="text-white">{{ parseFloat(deal.progress / 18 * 100).toFixed(0) }}%</p></div></td>
									<td class="w-7/12">
										<div class="bg-green-500 rounded-full p-2 text-white cursor-pointer" :style="'background-color: rgba(79, 88, 219, 0.5)'" @click="goToDeal(deal.id)">{{deal.stage}}</div></td>
									<td class="w-2/12px-6 pt-3 pb-3">{{deal.sum}}</td>
								</tr>
							</table>
						</div>
						<!--/Metric Card-->
					</div>

					
					
					<div class="w-full md:w-1/2 xl:w-full p-0">
						<!--Metric Card-->
						<div class="bg-white border rounded shadow-ss p-4" @click="pyramid">
							<div class="flex flex-row items-center">
								<div class="flex-shrink pr-4">
									<div class="rounded p-3 bg-yellow-600"><i class="fas fa-user-plus fa-2x fa-fw fa-inverse"></i></div>
								</div>
								<div>
									<h5 class="font-bold uppercase text-gray-500">Количество сделок</h5>	
								</div>
								<div class="flex-1 text-right md:text-center">
									
									<h3 class="font-bold text-xl">{{this.findeals}} из {{ this.deals }} </h3>
								</div>
								<div>
									<div class="text-3xl font-bold text-indigo-500">
										{{ parseFloat(this.deals / 100 * 100).toFixed(0) }}%
									</div>
								</div>
							</div>
						</div>
						<!--/Metric Card-->
						<div v-if="showPyramid" class="flex justify-between pt-5">
							<div class=" border-2 p-3 rounded-lg mybg">
								<h3><b>Этап 1</b></h3>
								<br>
								<table class="space-y-2">
									<tr>
										<th>1.</th>
										<th class="text-left">Новый заказ</th>
										<td class="pl-16">{{stage[0]}}</td>
									</tr>
									<tr>
										<th>2.</th>
										<th class="text-left">Согласование с производством</th>
										<td class="pl-16">{{stage[1]}}</td>
									</tr>
									<tr>
										<th>3.</th>
										<th class="text-left">Расчет логистики / ТС</th>
										<td class="pl-16">{{stage[2]}}</td>
									</tr>
									<tr>
										<th>4.</th>
										<th class="text-left">Расчет сервиса</th>
										<td class="pl-16">{{stage[3]}}</td>
									</tr>
									<tr>
										<th>5.</th>
										<th class="text-left">Внутреннее согласование КП</th>
										<td class="pl-16">{{stage[4]}}</td>
									</tr>
									<tr>
										<th>6.</th>
										<th class="text-left">Согласование КП с заказчиком</th>
										<td class="pl-16">{{stage[5]}}</td>
									</tr>
									<tr>
										<th>7.</th>
										<th class="text-left">Составление договора с закачиком</th>
										<td class="pl-16">{{stage[6]}}</td>
									</tr>
									<tr>
										<th>8.</th>
										<th class="text-left">Согласование договора с заказчиком</th>
										<td class="pl-16">{{stage[7]}}</td>
									</tr>
									<tr>
										<th>9.</th>
										<th class="text-left">Заключение договора с заказчиком</th>
										<td class="pl-16">{{stage[8]}}</td>
									</tr>
									<tr>
										<th>10.</th>
										<th class="text-left">Выставление счета  заказчику</th>
										<td class="pl-16">{{stage[9]}}</td>
									</tr>
								</table>
							
							</div>
							<div class=" border-2 p-3 rounded-lg mybg">
								<h3><b>Этап 2</b></h3>
								<br>
								<table class="space-y-2">
									<tr>
										<th>1.</th>
										<th class="text-left">Принято в работу</th>
										<td class="pl-16">{{stage[10]}}</td>
									</tr>
									<tr>
										<th>2.</th>
										<th class="text-left">Исполнение заявки на производство</th>
										<td class="pl-16">{{stage[11]}}</td>
									</tr>
									<tr>
										<th>3.</th>
										<th class="text-left">Отпуск готовой продукции  (внутреннее перемещение)</th>
										<td class="pl-16">{{stage[12]}}</td>
									</tr>
									<tr>
										<th>4.</th>
										<th class="text-left">Подготовка полного пакета документов для сдачи продукции заказчику</th>
										<td class="pl-16">{{stage[13]}}</td>
									</tr>
									<tr>
										<th>5.</th>
										<th class="text-left">Логистика (при необходимости)</th>
										<td class="pl-16">{{stage[14]}}</td>
									</tr>
									<tr>
										<th>6.</th>
										<th class="text-left">Поставка и ввод в эксплуатацию</th>
										<td class="pl-16">{{stage[15]}}</td>
									</tr>
									<tr>
										<th>7.</th>
										<th class="text-left">Исполнение договора</th>
										<td class="pl-16">{{stage[16]}}</td>
									</tr>
									<tr>
										<th>8.</th>
										<th class="text-left">Завершен</th>
										<td class="pl-16">{{stage[17]}}</td>
									</tr>
								
								</table>
							</div>
						</div>

						
							
						
					</div>
					
					<div class="w-full md:w-1/2 xl:w-full p-0">
						<!--Metric Card-->
						<div class="bg-white border rounded shadow-ss p-4">
							<div class="flex flex-row items-center">
								<div class="flex-shrink pr-4">
									<div class="rounded p-3 bg-indigo-600"><i class="fas fa-tasks fa-2x fa-fw fa-inverse"></i></div>
								</div>
								<div>
									<h5 class="font-bold uppercase text-gray-500">Количество клиентов</h5>
								</div>
								<div class="flex-1 text-right md:text-center">
									
									<h3 class="font-bold text-xl">{{ this.organizations }}</h3>
								</div>
								<div>
									<!--<div class="text-3xl font-bold text-indigo-500">
										{{ parseFloat(this.organizations / 30 * 100).toFixed(0) }}%
									</div>-->
								</div>
							</div>
						</div>
						<!--/Metric Card-->
					</div>

					<div class="bg-white border rounded shadow-ss p-4" @click="showManagers()">
							<div class="flex flex-row items-center">
								<div class="flex-shrink pr-4">
									<div class="rounded p-3 bg-yellow-600"><i class="fas fa-user-plus fa-2x fa-fw fa-inverse"></i></div>
								</div>
								<div>
									<h5 class="font-bold uppercase text-gray-500">Отчет по менеджерам</h5>	
								</div>
								<div>
								</div>
							</div>
						</div>

						<div v-if="managershow">
							<table class="w-full whitespace-nowrap  ">
					    		<tr class="text-left font-bold border-b border-gray-200">

						            <th class="px-6 pt-4 pb-4 flex">
						            	<p class="font-bold text-center">Менеджер</p>
						            </th>
						            <th class="px-6 pt-4 pb-4">
						            	<p class="font-bold text-center">Клиенты</p>
						            </th>
						             <th class="px-6 pt-4 pb-4">
						            	<p class="font-bold text-center">Сделки</p>
						            </th>
						            <th class="px-6 pt-4 pb-4">
						            	<p class="font-bold text-center">
											Сумма
										</p> 
						            </th>

						            <th class="px-6 pt-4 pb-4">
						            	<p class="font-bold text-center">
											Ф. Сумма
										</p> 
						            </th>
						          
						        </tr>

						        <tr v-for="manager in managers" class="text-center hover:bg-gray-100 focus-within:bg-gray-100 mb-3" >
						        	<td class="px-6 pt-3 pb-3 w-8">
										<div class="flex">
											<p class="text-sm">{{manager.name}}</p>
										</div>
					               </td>  
						       	   <td class="px-6 pt-3 pb-3 w-8">
						       	   		<p class="text-sm">{{manager.clients}}</p>
					               </td>      
					               <td class="px-6 pt-3 pb-3 w-8">
					               		<p class="text-sm">{{manager.deals}}</p>
					               </td> 
					               <td class="px-6 pt-3 pb-3 w-8">
					               		<p>{{manager.sum}}</p>

					               </td> 
					                <td class="px-6 pt-3 pb-3 w-8">
					               		<p>{{manager.fsum}}</p>

					               </td> 
						       	</tr>

					   		</table>
						</div>
					</div>
					
					<div class="w-6/12 pl-3 mb-5">
						<!-- <AChart class="w-full" /> -->

					</div>
				</div>
			</div>


			<div class="px-6 flex-auto pb-12 mb-2" v-if="show == 2">
                <template v-if="plans.length > 0">


                    <div class="flex flex-col gap-6 w-full py-4">
                        <div  v-for="plan in plans" :key="plan.id">
                            
                            
                            <div class="flex gap-3 justify-between">
                                <div class="font-medium mb-2">{{ plan.name }}</div>
                                <div class="font-normal">{{ plan.plan }}</div>
                            </div>
                            <div class="shadow w-full bg-grey-light rounded-lg overflow-hidden">
                                <div class="text-xs leading-none py-1 text-center text-white" :class="'bg-' + plantypes[plan.type] + '-500'" :style="'width: ' + plan.fact +'%'"> {{plan.fact}}%</div>
                            </div>


                        </div>
                        
                    </div>


                </template>

                <template v-else>
                    <div class="flex flex-col gap-6 w-full py-4 text-sm">
                        У сотрудника нет установленных планов
                    </div> 
                </template>
            </div>
			
			<!--<div class="border-1 rounded-lg pb-5 flex  flex-auto" v-if="show == 1">	
				
				 <div class="flex justify-between">
					<div>
						Продажи
					</div>
					<div class="flex gap-4">
						<button class="border-1 w-24 border-black rounded-full p-1 ">неделя</button>
						<button class="border-2 w-24 border-black rounded-full p-1">месяц</button>
						<button class="border-2  w-24 border-black rounded-full p-1">квартал</button>
						<button class="border-2  w-24 border-black rounded-full p-1">год</button>
					</div>
					
				</div> -->
			
				<!-- <SalesChart class="flex-auto flex flex-col h-full w-6/12 pr-3"/>
				<ClientChart class="w-6/12 pl-3"/> 
			</div>-->
			<div class="flex">
<div class="w-6/12 h-6/12">
<svg
   xmlns:d="https://loading.io/stock/"
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   viewBox="0 0 100 100"
   width="258px"
   height="258px"
   style="width:100%;height:100%;background-size:initial;background-repeat-y:initial;background-repeat-x:initial;background-position-y:initial;background-position-x:initial;background-origin:initial;background-image:initial;background-color:rgb(255, 255, 255);background-clip:initial;background-attachment:initial;animation-play-state:paused"
   version="1.1"
   id="svg42"
   sodipodi:docname="pyramid.svg"
   inkscape:version="1.0.2-2 (e86c870879, 2021-01-15)">
  <metadata
     id="metadata48">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <defs
     id="defs46" />
  <sodipodi:namedview
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1"
     objecttolerance="10"
     gridtolerance="10"
     guidetolerance="10"
     inkscape:pageopacity="0"
     inkscape:pageshadow="2"
     inkscape:window-width="1920"
     inkscape:window-height="1001"
     id="namedview44"
     showgrid="false"
     inkscape:zoom="2.8284271"
     inkscape:cx="120.51845"
     inkscape:cy="130.86382"
     inkscape:window-x="-9"
     inkscape:window-y="-9"
     inkscape:window-maximized="1"
     inkscape:current-layer="g40"
     showguides="false" />
  <g
     class="ldl-scale"
     id="g40"
     transform="matrix(0.42510891,0,0,0.33263469,15.758433,1.0277157)">
    <path
       fill="#e15b64"
       d="m 11.291636,199.10971 c -0.23054,0.9085 -0.675482,1.44996 -1.190507,1.44996 -0.5150249,0 -0.9599667,-0.54146 -1.1905052,-1.44996 L 6.2972266,188.81275 h 7.6078064 z"
       style="fill:#e15b64;stroke-width:0.647218"
       id="path30" />
    <path
       d="M 5.6056071,186.08726 1.3747439,169.41353 H 18.827515 l -4.230863,16.67373 z"
       fill="#f8b26a"
       style="fill:#f8b26a;stroke-width:0.647218"
       id="path32" />
    <path
       d="M 0.68312521,166.68803 -3.5481986,150.0143 H 23.750458 l -4.231323,16.67373 z"
       fill="#abbd81"
       style="fill:#abbd81;stroke-width:0.647218"
       id="path34" />
    <path
       fill="#a0c8d7"
       d="m -7.4484683,131.85972 a 1.3048542,2.5710582 0 0 1 1.1319493,-1.24373 h 32.835758 a 1.3048542,2.5710582 0 0 1 1.13195,1.24373 c 0.24806,0.7904 0.26927,1.72071 0.05855,2.55108 l -3.267668,12.878 H -4.2398172 l -3.267669,-12.878 a 1.3053152,2.5719668 0 0 1 0.059018,-2.55108 z"
       style="fill:#a0c8d7;stroke-width:0.647218"
       id="path36" />
    <metadata
       id="metadata38">
      <d:name
         style="animation-play-state:paused">pyramid</d:name>
      <d:tags
         style="animation-play-state:paused">pyramid,funnel,step,hierarchy,layer,stage,level,infographics</d:tags>
      <d:license
         style="animation-play-state:paused">by</d:license>
      <d:slug
         style="animation-play-state:paused">bbxt7q</d:slug>
    </metadata>
    <path
       fill="#a0c8d7"
       d="m -11.152468,112.50039 a 1.6202056,2.5710582 0 0 1 1.4055152,-1.24374 H 31.024405 a 1.6202056,2.5710582 0 0 1 1.405515,1.24374 c 0.308009,0.7904 0.334345,1.7207 0.0727,2.55107 l -4.057386,12.87799 H -7.1683643 l -4.0573837,-12.87799 a 1.6207781,2.5719668 0 0 1 0.07328,-2.55107 z"
       style="fill:#a0c8d7;stroke-width:0.721203"
       id="path50" />
    <path
       fill="#a0c8d7"
       d="m -15.136738,93.431772 a 1.9908601,2.5710582 0 0 1 1.727054,-1.243741 h 50.098628 a 1.9908601,2.5710582 0 0 1 1.727054,1.243741 c 0.378474,0.790399 0.410838,1.720699 0.08933,2.551074 L 33.519745,108.86085 H -10.24119 l -4.985594,-12.878004 a 1.9915636,2.5719668 0 0 1 0.09005,-2.551074 z"
       style="fill:#a0c8d7;stroke-width:0.799449"
       id="path52" />
    <path
       fill="#a0c8d7"
       d="m -20.251925,73.274787 a 2.3413041,2.5710582 0 0 1 2.031062,-1.24373 h 58.917305 a 2.3413041,2.5710582 0 0 1 2.031064,1.24373 c 0.445094,0.790399 0.483156,1.720709 0.105072,2.551073 l -5.86319,12.878011 H -14.494633 L -20.35782,75.82586 a 2.3421315,2.5719668 0 0 1 0.105895,-2.551073 z"
       style="fill:#a0c8d7;stroke-width:0.866965"
       id="path54" />
    <path
       fill="#a0c8d7"
       d="m -26.398839,53.851979 a 2.766615,2.5710582 0 0 1 2.400014,-1.243741 h 69.619967 a 2.766615,2.5710582 0 0 1 2.400014,1.243741 c 0.525944,0.790399 0.570914,1.720699 0.124149,2.551073 l -6.928272,12.878001 h -60.812736 l -6.928269,-12.878001 a 2.7675925,2.5719668 0 0 1 0.125133,-2.551073 z"
       style="fill:#a0c8d7;stroke-width:0.942415"
       id="path56" />
    <path
       fill="#a0c8d7"
       d="m -27.74788,35.209391 a 2.9240459,2.5710582 0 0 1 2.536582,-1.243741 h 73.581593 a 2.9240459,2.5710582 0 0 1 2.536593,1.243741 c 0.555878,0.790398 0.603402,1.720698 0.131215,2.551073 l -7.322507,12.878 h -64.273217 l -7.322513,-12.878 a 2.9250791,2.5719668 0 0 1 0.132254,-2.551073 z"
       style="fill:#a0c8d7;stroke-width:1.968857"
      
       id="path58" />
    <path
       fill="#a0c8d7"
       d="m -27.965306,16.586528 a 2.9713951,2.5710582 0 0 1 2.577659,-1.24373 h 74.773108 a 2.9713951,2.5710582 0 0 1 2.577666,1.24373 c 0.564872,0.7904 0.613178,1.72071 0.133344,2.55107 l -7.44109,12.878 h -65.313996 l -7.441086,-12.878 a 2.972445,2.5719668 0 0 1 0.134395,-2.55107 z"
       style="fill:#a0c8d7;stroke-width:0.976668"
       id="path60" />
    <text
       xml:space="preserve"
       style="font-size:6.83287px;line-height:1.25;font-family:sans-serif;stroke-width:0.759208"
       x="76.705894"
       y="21.002617"
       id="text66"
       transform="scale(0.90951159,1.0994912)"><tspan
         sodipodi:role="line"
         id="tspan64"
         x="76.705894"
         y="21.002617"
         style="font-size:6.83287px;stroke-width:0.759208">Новый заказ</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:3.03683px;line-height:1.25;font-family:sans-serif;stroke-width:0.759208"
       x="77.779579"
       y="40.060471"
       id="text70"
       transform="scale(0.90951159,1.0994912)"><tspan
         sodipodi:role="line"
         id="tspan68"
         x="77.779579"
         y="40.060471"
         style="font-size:6.83287px;stroke-width:0.759208">Согласование с производством</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:9.2766px;line-height:1.25;font-family:sans-serif;stroke-width:1.03073"
       x="8.019165"
       y="24.598454"
       id="text114"
       transform="scale(0.88457297,1.130489)"><tspan
         sodipodi:role="line"
         id="tspan112"
         x="8.019165"
         y="24.598454"
         style="font-size:9.2766px;stroke-width:1.03073">{{Math.round(stagecounter[0]*100)+'%'}}</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:9.2766px;line-height:1.25;font-family:sans-serif;stroke-width:1.03073;-inkscape-font-specification:'sans-serif, Normal';font-weight:normal;font-style:normal;font-stretch:normal;font-variant:normal;font-variant-ligatures:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-east-asian:normal;"
       x="8.019165"
       y="40.632904"
       id="text118"
       transform="scale(0.88457296,1.130489)"><tspan
         sodipodi:role="line"
         id="tspan120">{{Math.round(stagecounter[1]*100)+'%'}}</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:9.2766px;line-height:1.25;font-family:sans-serif;-inkscape-font-specification:'sans-serif, Normal';font-variant-ligatures:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-east-asian:normal;stroke-width:1.03073;"
       x="8.019165"
       y="57.396194"
       id="text124"
       transform="scale(0.88457296,1.130489)"><tspan
         sodipodi:role="line"
         id="tspan150">{{Math.round(stagecounter[2]*100)+'%'}}</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:9.2766px;line-height:1.25;font-family:sans-serif;-inkscape-font-specification:'sans-serif, Normal';font-variant-ligatures:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-east-asian:normal;stroke-width:1.03073;"
       x="8.383584"
       y="73.795067"
       id="text128"
       transform="scale(0.88457296,1.130489)"><tspan
         sodipodi:role="line"
         id="tspan152">{{Math.round(stagecounter[3]*100)+'%'}}</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:9.2766px;line-height:1.25;font-family:sans-serif;-inkscape-font-specification:'sans-serif, Normal';font-variant-ligatures:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-east-asian:normal;stroke-width:1.03073;"
       x="8.748003"
       y="92.016037"
       id="text132"
       transform="scale(0.88457296,1.130489)"><tspan
         sodipodi:role="line"
         id="tspan154">{{Math.round(stagecounter[4]*100)+'%'}}</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:9.2766px;line-height:1.25;font-family:sans-serif;-inkscape-font-specification:'sans-serif, Normal';font-variant-ligatures:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-east-asian:normal;stroke-width:1.03073;"
       x="8.383584"
       y="109.50816"
       id="text136"
       transform="scale(0.88457296,1.130489)"><tspan
         sodipodi:role="line"
         id="tspan156">{{Math.round(stagecounter[5]*100)+'%'}}</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:9.2766px;line-height:1.25;font-family:sans-serif;-inkscape-font-specification:'sans-serif, Normal';font-variant-ligatures:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-east-asian:normal;stroke-width:1.03073;"
       x="8.383584"
       y="126.63587"
       id="text140"
       transform="scale(0.88457296,1.130489)"><tspan
         sodipodi:role="line"
         id="tspan158">{{Math.round(stagecounter[6]*100)+'%'}}</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:9.2766px;line-height:1.25;font-family:sans-serif;-inkscape-font-specification:'sans-serif, Normal';font-variant-ligatures:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-east-asian:normal;stroke-width:1.03073;"
       x="8.383584"
       y="142.67032"
       id="text144"
       transform="scale(0.88457296,1.130489)"><tspan
         sodipodi:role="line"
         id="tspan160">{{Math.round(stagecounter[7]*100)+'%'}}</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:9.2766px;line-height:1.25;font-family:sans-serif;-inkscape-font-specification:'sans-serif, Normal';font-variant-ligatures:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-east-asian:normal;stroke-width:1.03073;"
       x="8.383584"
       y="161.62012"
       id="text148"
       transform="scale(0.88457296,1.130489)"><tspan
         sodipodi:role="line"
         id="tspan162">{{Math.round(stagecounter[8]*100)+'%'}}</tspan></text>
  </g>
  <!-- generated by https://loading.io/ -->
  <text
     xml:space="preserve"
     style="font-size:2.56943px;line-height:1.25;font-family:sans-serif;stroke-width:0.285492"
     x="44.022499"
     y="23.287537"
     id="text74"
     transform="scale(1.0281928,0.97258023)"><tspan
       sodipodi:role="line"
       id="tspan72"
       x="44.022499"
       y="23.287537"
       style="font-size:2.56943px;stroke-width:0.285492">Расчет логистики  / ТС</tspan></text>
  <text
     xml:space="preserve"
     style="font-size:2.56943px;line-height:1.25;font-family:sans-serif;stroke-width:0.285492"
     x="44.42625"
     y="29.343742"
     id="text78"
     transform="scale(1.0281928,0.97258023)"><tspan
       sodipodi:role="line"
       id="tspan76"
       x="44.42625"
       y="29.343742"
       style="font-size:2.56943px;stroke-width:0.285492">Расчет сервиса</tspan></text>
  <text
     xml:space="preserve"
     style="font-size:2.56943px;line-height:1.25;font-family:sans-serif;stroke-width:0.285492"
     x="44.829994"
     y="35.904636"
     id="text82"
     transform="scale(1.0281928,0.97258023)"><tspan
       sodipodi:role="line"
       id="tspan80"
       x="44.829994"
       y="35.904636"
       style="font-size:2.56943px;stroke-width:0.285492">Внутреннее согласование КП</tspan></text>
  <text
     xml:space="preserve"
     style="font-size:2.56943px;line-height:1.25;font-family:sans-serif;stroke-width:0.285492"
     x="43.826805"
     y="42.768337"
     id="text86"
     transform="scale(1.0281928,0.97258023)"><tspan
       sodipodi:role="line"
       id="tspan84"
       x="43.826805"
       y="42.768337"
       style="font-size:2.56943px;stroke-width:0.285492">Согласование КП с заказчиком</tspan></text>
  <text
     xml:space="preserve"
     style="font-size:2.56943px;line-height:1.25;font-family:sans-serif;stroke-width:0.285492"
     x="44.022499"
     y="50.641407"
     id="text90"
     transform="scale(1.0281928,0.97258023)"><tspan
       sodipodi:role="line"
       id="tspan88"
       x="44.022499"
       y="50.641407"
       style="font-size:2.56943px;stroke-width:0.285492">Составление договора с заказчиком</tspan></text>
  <text
     xml:space="preserve"
     style="font-size:2.56943px;line-height:1.25;font-family:sans-serif;stroke-width:0.285492"
     x="44.022499"
     y="56.596684"
     id="text94"
     transform="scale(1.0281928,0.97258023)"><tspan
       sodipodi:role="line"
       id="tspan92"
       x="44.022499"
       y="56.596684"
       style="font-size:2.56943px;stroke-width:0.285492">Согласование договора с заказчиком</tspan></text>
  <text
     xml:space="preserve"
     style="font-size:2.56943px;line-height:1.25;font-family:sans-serif;stroke-width:0.285492"
     x="44.32531"
     y="62.259583"
     id="text98"
     transform="scale(1.0281928,0.97258023)"><tspan
       sodipodi:role="line"
       id="tspan96"
       x="44.32531"
       y="62.259583"
       style="font-size:2.56943px;stroke-width:0.285492">Выставление счета заказчику</tspan></text>
  <text
     xml:space="preserve"
     style="font-size:2.56943px;line-height:1.25;font-family:sans-serif;stroke-width:0.285492"
     x="44.729057"
     y="68.406281"
     id="text102"
     transform="scale(1.0281928,0.97258023)"><tspan
       sodipodi:role="line"
       id="tspan100"
       x="44.729057"
       y="68.406281"
       style="font-size:2.56943px;stroke-width:0.285492">Завершено</tspan></text>
</svg>


</div>
<div  class="w-6/12 h-6/12">

<svg
   xmlns:d="https://loading.io/stock/"
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
   xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
   viewBox="0 0 100 100"
   width="258px"
   height="258px"
   style="width:100%;height:100%;background-size:initial;background-repeat-y:initial;background-repeat-x:initial;background-position-y:initial;background-position-x:initial;background-origin:initial;background-image:initial;background-color:rgb(255, 255, 255);background-clip:initial;background-attachment:initial;animation-play-state:paused"
   version="1.1"
   id="svg42"
   sodipodi:docname="pyramid.svg"
   inkscape:version="1.0.2-2 (e86c870879, 2021-01-15)">
  <metadata
     id="metadata48">
    <rdf:RDF>
      <cc:Work
         rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type
           rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
        <dc:title></dc:title>
      </cc:Work>
    </rdf:RDF>
  </metadata>
  <defs
     id="defs46" />
  <sodipodi:namedview
     pagecolor="#ffffff"
     bordercolor="#666666"
     borderopacity="1"
     objecttolerance="10"
     gridtolerance="10"
     guidetolerance="10"
     inkscape:pageopacity="0"
     inkscape:pageshadow="2"
     inkscape:window-width="1920"
     inkscape:window-height="1001"
     id="namedview44"
     showgrid="false"
     inkscape:zoom="2.8284271"
     inkscape:cx="120.51845"
     inkscape:cy="130.86382"
     inkscape:window-x="-9"
     inkscape:window-y="-9"
     inkscape:window-maximized="1"
     inkscape:current-layer="g40"
     showguides="false" />
  <g
     class="ldl-scale"
     id="g40"
     transform="matrix(0.42510891,0,0,0.33263469,15.758433,1.0277157)">

    <path
       d="M 0.68312521,166.68803 -3.5481986,150.0143 H 23.750458 l -4.231323,16.67373 z"
       fill="#abbd81"
       style="fill:#abbd81;stroke-width:0.647218"
       id="path62" />
    <path
       fill="#a0c8d7"
       d="m -7.4484683,131.85972 a 1.3048542,2.5710582 0 0 1 1.1319493,-1.24373 h 32.835758 a 1.3048542,2.5710582 0 0 1 1.13195,1.24373 c 0.24806,0.7904 0.26927,1.72071 0.05855,2.55108 l -3.267668,12.878 H -4.2398172 l -3.267669,-12.878 a 1.3053152,2.5719668 0 0 1 0.059018,-2.55108 z"
       style="fill:#a0c8d7;stroke-width:0.647218"
       id="path64" />
    <metadata
       id="metadata38">
      <d:name
         style="animation-play-state:paused">pyramid</d:name>
      <d:tags
         style="animation-play-state:paused">pyramid,funnel,step,hierarchy,layer,stage,level,infographics</d:tags>
      <d:license
         style="animation-play-state:paused">by</d:license>
      <d:slug
         style="animation-play-state:paused">bbxt7q</d:slug>
    </metadata>
    <path
       fill="#a0c8d7"
       d="m -11.152468,112.50039 a 1.6202056,2.5710582 0 0 1 1.4055152,-1.24374 H 31.024405 a 1.6202056,2.5710582 0 0 1 1.405515,1.24374 c 0.308009,0.7904 0.334345,1.7207 0.0727,2.55107 l -4.057386,12.87799 H -7.1683643 l -4.0573837,-12.87799 a 1.6207781,2.5719668 0 0 1 0.07328,-2.55107 z"
       style="fill:#a0c8d7;stroke-width:0.721203"
       id="path66" />
    <path
       fill="#a0c8d7"
       d="m -15.136738,93.431772 a 1.9908601,2.5710582 0 0 1 1.727054,-1.243741 h 50.098628 a 1.9908601,2.5710582 0 0 1 1.727054,1.243741 c 0.378474,0.790399 0.410838,1.720699 0.08933,2.551074 L 33.519745,108.86085 H -10.24119 l -4.985594,-12.878004 a 1.9915636,2.5719668 0 0 1 0.09005,-2.551074 z"
       style="fill:#a0c8d7;stroke-width:0.799449"
       id="path68" />
    <path
       fill="#a0c8d7"
       d="m -20.251925,73.274787 a 2.3413041,2.5710582 0 0 1 2.031062,-1.24373 h 58.917305 a 2.3413041,2.5710582 0 0 1 2.031064,1.24373 c 0.445094,0.790399 0.483156,1.720709 0.105072,2.551073 l -5.86319,12.878011 H -14.494633 L -20.35782,75.82586 a 2.3421315,2.5719668 0 0 1 0.105895,-2.551073 z"
       style="fill:#a0c8d7;stroke-width:0.866965"
       id="path70" />
    <path
       fill="#a0c8d7"
       d="m -26.398839,53.851979 a 2.766615,2.5710582 0 0 1 2.400014,-1.243741 h 69.619967 a 2.766615,2.5710582 0 0 1 2.400014,1.243741 c 0.525944,0.790399 0.570914,1.720699 0.124149,2.551073 l -6.928272,12.878001 h -60.812736 l -6.928269,-12.878001 a 2.7675925,2.5719668 0 0 1 0.125133,-2.551073 z"
       style="fill:#a0c8d7;stroke-width:0.942415"
       id="path72" />
    <path
       fill="#a0c8d7"
       d="m -27.74788,35.209391 a 2.9240459,2.5710582 0 0 1 2.536582,-1.243741 h 73.581593 a 2.9240459,2.5710582 0 0 1 2.536593,1.243741 c 0.555878,0.790398 0.603402,1.720698 0.131215,2.551073 l -7.322507,12.878 h -64.273217 l -7.322513,-12.878 a 2.9250791,2.5719668 0 0 1 0.132254,-2.551073 z"
       style="fill:#a0c8d7;stroke-width:1.968857"
      
       id="path74" />
    <path
       fill="#a0c8d7"
       d="m -27.965306,16.586528 a 2.9713951,2.5710582 0 0 1 2.577659,-1.24373 h 74.773108 a 2.9713951,2.5710582 0 0 1 2.577666,1.24373 c 0.564872,0.7904 0.613178,1.72071 0.133344,2.55107 l -7.44109,12.878 h -65.313996 l -7.441086,-12.878 a 2.972445,2.5719668 0 0 1 0.134395,-2.55107 z"
       style="fill:#a0c8d7;stroke-width:0.976668"
       id="path76" />
    <text
       xml:space="preserve"
       style="font-size:6.83287px;line-height:1.25;font-family:sans-serif;stroke-width:0.759208"
       x="76.705894"
       y="21.002617"
       id="text66"
       transform="scale(0.90951159,1.0994912)"><tspan
         sodipodi:role="line"
         id="tspan64"
         x="76.705894"
         y="21.002617"
         style="font-size:6.83287px;stroke-width:0.759208">Принято в работу</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:3.03683px;line-height:1.25;font-family:sans-serif;stroke-width:0.759208"
       x="77.779579"
       y="40.060471"
       id="text70"
       transform="scale(0.90951159,1.0994912)"><tspan
         sodipodi:role="line"
         id="tspan68"
         x="77.779579"
         y="40.060471"
         style="font-size:6.83287px;stroke-width:0.759208">Исполнение заявки на производство</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:9.2766px;line-height:1.25;font-family:sans-serif;stroke-width:1.03073"
       x="8.019165"
       y="24.598454"
       id="text114"
       transform="scale(0.88457297,1.130489)"><tspan
         sodipodi:role="line"
         id="tspan112"
         x="8.019165"
         y="24.598454"
         style="font-size:9.2766px;stroke-width:1.03073">{{Math.round(stagecounter[9]*100)+'%'}}</tspan></text>
    <text
       xml:space="preserve"
       style="font-size:9.2766px;line-height:1.25;font-family:sans-serif;stroke-width:1.03073;-inkscape-font-specification:'sans-serif, Normal';font-weight:normal;font-style:normal;font-stretch:normal;font-variant:normal;font-variant-ligatures:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-east-asian:normal;"
       x="8.019165"
       y="40.632904"
       id="text118"
       transform="scale(0.88457296,1.130489)"><tspan
         sodipodi:role="line"
         id="tspan120">{{Math.round(stagecounter[10]*100)+'%'}}</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:9.2766px;line-height:1.25;font-family:sans-serif;-inkscape-font-specification:'sans-serif, Normal';font-variant-ligatures:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-east-asian:normal;stroke-width:1.03073;"
       x="8.019165"
       y="57.396194"
       id="text124"
       transform="scale(0.88457296,1.130489)"><tspan
         sodipodi:role="line"
         id="tspan150">{{Math.round(stagecounter[11]*100)+'%'}}</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:9.2766px;line-height:1.25;font-family:sans-serif;-inkscape-font-specification:'sans-serif, Normal';font-variant-ligatures:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-east-asian:normal;stroke-width:1.03073;"
       x="8.383584"
       y="73.795067"
       id="text128"
       transform="scale(0.88457296,1.130489)"><tspan
         sodipodi:role="line"
         id="tspan152">{{Math.round(stagecounter[12]*100)+'%'}}</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:9.2766px;line-height:1.25;font-family:sans-serif;-inkscape-font-specification:'sans-serif, Normal';font-variant-ligatures:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-east-asian:normal;stroke-width:1.03073;"
       x="8.748003"
       y="92.016037"
       id="text132"
       transform="scale(0.88457296,1.130489)"><tspan
         sodipodi:role="line"
         id="tspan154">{{Math.round(stagecounter[13]*100)+'%'}}</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:9.2766px;line-height:1.25;font-family:sans-serif;-inkscape-font-specification:'sans-serif, Normal';font-variant-ligatures:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-east-asian:normal;stroke-width:1.03073;"
       x="8.383584"
       y="109.50816"
       id="text136"
       transform="scale(0.88457296,1.130489)"><tspan
         sodipodi:role="line"
         id="tspan156">{{Math.round(stagecounter[14]*100)+'%'}}</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:9.2766px;line-height:1.25;font-family:sans-serif;-inkscape-font-specification:'sans-serif, Normal';font-variant-ligatures:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-east-asian:normal;stroke-width:1.03073;"
       x="8.383584"
       y="126.63587"
       id="text140"
       transform="scale(0.88457296,1.130489)"><tspan
         sodipodi:role="line"
         id="tspan158">{{Math.round(stagecounter[15]*100)+'%'}}</tspan></text>
    <text
       xml:space="preserve"
       style="font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:9.2766px;line-height:1.25;font-family:sans-serif;-inkscape-font-specification:'sans-serif, Normal';font-variant-ligatures:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-east-asian:normal;stroke-width:1.03073;"
       x="8.383584"
       y="142.67032"
       id="text144"
       transform="scale(0.88457296,1.130489)"><tspan
         sodipodi:role="line"
         id="tspan160">{{Math.round(stagecounter[16]*100)+'%'}}</tspan></text>

  </g>
  <!-- generated by https://loading.io/ -->
  <text
     xml:space="preserve"
     style="font-size:2.56943px;line-height:1.25;font-family:sans-serif;stroke-width:0.285492"
     x="44.022499"
     y="23.287537"
     id="text74"
     transform="scale(1.0281928,0.97258023)"><tspan
       sodipodi:role="line"
       id="tspan72"
       x="44.022499"
       y="23.287537"
       style="font-size:2.56943px;stroke-width:0.285492">Отпуск готовой продукции</tspan></text>
  <text
     xml:space="preserve"
     style="font-size:2.56943px;line-height:1.25;font-family:sans-serif;stroke-width:0.285492"
     x="44.42625"
     y="29.343742"
     id="text78"
     transform="scale(1.0281928,0.97258023)"><tspan
       sodipodi:role="line"
       id="tspan76"
       x="44.42625"
       y="29.343742"
       style="font-size:2.56943px;stroke-width:0.285492">Подготовка полного пакета</tspan></text>
  <text
     xml:space="preserve"
     style="font-size:2.56943px;line-height:1.25;font-family:sans-serif;stroke-width:0.285492"
     x="44.829994"
     y="35.904636"
     id="text82"
     transform="scale(1.0281928,0.97258023)"><tspan
       sodipodi:role="line"
       id="tspan80"
       x="44.829994"
       y="35.904636"
       style="font-size:2.56943px;stroke-width:0.285492">Логистика</tspan></text>
  <text
     xml:space="preserve"
     style="font-size:2.56943px;line-height:1.25;font-family:sans-serif;stroke-width:0.285492"
     x="43.826805"
     y="42.768337"
     id="text86"
     transform="scale(1.0281928,0.97258023)"><tspan
       sodipodi:role="line"
       id="tspan84"
       x="43.826805"
       y="42.768337"
       style="font-size:2.56943px;stroke-width:0.285492">Поставка и ввод в эксплуатацию</tspan></text>
  <text
     xml:space="preserve"
     style="font-size:2.56943px;line-height:1.25;font-family:sans-serif;stroke-width:0.285492"
     x="44.022499"
     y="50.641407"
     id="text90"
     transform="scale(1.0281928,0.97258023)"><tspan
       sodipodi:role="line"
       id="tspan88"
       x="44.022499"
       y="50.641407"
       style="font-size:2.56943px;stroke-width:0.285492">Исполнение договора</tspan></text>
  <text
     xml:space="preserve"
     style="font-size:2.56943px;line-height:1.25;font-family:sans-serif;stroke-width:0.285492"
     x="44.022499"
     y="56.596684"
     id="text94"
     transform="scale(1.0281928,0.97258023)"><tspan
       sodipodi:role="line"
       id="tspan92"
       x="44.022499"
       y="56.596684"
       style="font-size:2.56943px;stroke-width:0.285492">Завершен</tspan></text>
  
</svg>


</div>
</div>

			<div class="border-1 rounded-lg" v-if="show == 1">
				
			</div>



		</div>
   </div>

      <modal name="subtask"> 
        <create-subtask :task_id="task_id"></create-subtask>
      </modal>

  </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import Icon from '@/Shared/Icon'
import PersonCard from '@/Shared/PersonCard.vue'
import SalesChart from '@/Shared/SalesChart'
import ClientChart from '@/Shared/ClientChart'
import AChart from '@/Shared/AChart'
import FusionChart from '@/Shared/FChart'
import createReport from './Create.vue'
import axios from 'axios'
import $ from 'jquery'


export default {
	metaInfo: { title: 'Отчеты' },
	layout: Layout,
	components: {
	    Icon,
		PersonCard,
		SalesChart,
		ClientChart,
		AChart,
		'fchart' : FusionChart,
		createReport
	  },
	 props:{
	 	sdelki : Array,
	 	reports: Array,
	 	deals: Number,
	 	users: Array,
	 	sum: Number,
	 	managers: Array,
	 	organizations: Number,
	 	stage: Array,
	 	stagecounter: Array,
	 },
	 watch: {
        selectedUser: function (user) {
            console.log(user)
			if(user == null) {
				this.show = 1
			} else {
				this.show = 2

				axios.post('/get-plans', {
					id: user.code
				})
				.then(response => {
					this.plans = response.data
				})
			}
			


        }
    },
	data () {
    	return {    
    		managershow: false,
    		total: 0,
    		findeals: 0,
    		task_id: {
		      type: Number,
		      default: 0
		    },
    		period: 0,
    		showDeals: false,
    		showPyramid: false,
			selectedUser: {},
			show: 1,  	
			plans: [],  	
			color: "#875FDA",
			color1: "#4A32E3",
			angle: '50',
			icon: "download",
			plantypes: {
                1: 'green',
                2: 'skyblue',
                3: 'orange',
                4: 'indigo',
            },
             type: "pyramid",
		    width: "100%",
		    height: "100%",
		    dataFormat: "json",
		    
		}
  	},
  	created() {
  	
  		
  		
  		       
  	},
	  mounted(){
		$('#path32').attr("transform", "scale("+this.stagecounter[8]+",1)");
		$('#path34').attr("transform", "scale("+this.stagecounter[7]+",1)");
		$('#path36').attr("transform", "scale("+this.stagecounter[6]+",1)");
		$('#path50').attr("transform", "scale("+this.stagecounter[5]+",1)");
		$('#path52').attr("transform", "scale("+this.stagecounter[4]+",1)");
		$('#path54').attr("transform", "scale("+this.stagecounter[3]+",1)");
		$('#path56').attr("transform", "scale("+this.stagecounter[2]+",1)");
		$('#path58').attr("transform", "scale("+this.stagecounter[1]+",1)");
		$('#path60').attr("transform", "scale("+this.stagecounter[0]+",1)");

		$('#path62').attr("transform", "scale("+this.stagecounter[16]+",1)");
		$('#path64').attr("transform", "scale("+this.stagecounter[15]+",1)");
		$('#path66').attr("transform", "scale("+this.stagecounter[14]+",1)");
		$('#path68').attr("transform", "scale("+this.stagecounter[13]+",1)");
		$('#path70').attr("transform", "scale("+this.stagecounter[12]+",1)");

		$('#path72').attr("transform", "scale("+this.stagecounter[11]+",1)");
		$('#path74').attr("transform", "scale("+this.stagecounter[10]+",1)");
		$('#path76').attr("transform", "scale("+this.stagecounter[9]+",1)");
	},
  	computed:{
		gradient(){
			return `linear-gradient(${this.angle}deg, ${this.color1}, ${this.color})`;
		},

	},
	methods: {
		showManagers(){
			this.managershow = !this.managershow;
		},
		getDealsByDate(date){
		axios.post('/get-deals-bydate',{'date' : date.target.value})
        .then(response => {
        	this.sum = response.data.sum;
        	this.deals = response.data.deals;
        	this.total = response.data.total;
        	this.findeals = response.data.finisheddeals;
        });
		},
		goToDeal(id){
		
			window.location = '/deals/' + id + '/edit?tab=2';
		},
		showSum(){

			this.showDeals = !this.showDeals;
		},
		pyramid(){
			this.showPyramid = !this.showPyramid;
			//alert("check!");
		},
			openCreateModal() {
			this.$modal.show('create')
		},
		showPage(k) {
			this.show = k;
		}
	}
}
</script>
