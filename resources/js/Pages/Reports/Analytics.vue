<template>
	<div>Аналитика
  	<div class="grid grid-cols-2 gap-4 bg-white rounded-lg mt-8 p-6">
	  	<div class="border-2 rounded-lg  p-5">	
	  		<div class="flex justify-between">Продажи<button class="border-2 w-24 border-black rounded-full p-1">неделя</button><button class="border-2 w-24 border-black rounded-full p-1">месяц</button><button class="border-2  w-24 border-black rounded-full p-1">квартал</button><button class="border-2  w-24 border-black rounded-full p-1">год</button></div>
	  		<br>
	  		<SalesChart />
	  	</div>S
	  	<div class="border-2 rounded-lg">
	  		<div class="flex justify-between p-5">Клиенты<button class="border-2 w-24 border-black rounded-full p-1">неделя</button><button class="border-2 w-24 border-black rounded-full p-1">месяц</button><button class="border-2  w-24 border-black rounded-full p-1">квартал</button><button class="border-2  w-24 border-black rounded-full p-1">год</button></div>
D
  		</div>
  		<div class="border-2 rounded-lg p-5">Распределение сделок по менеджерам
  			<div class="relative flex justify-end">
  				<canvas class="absolute left-20 top-6" width="300" height="300" id="pie"></canvas>
  				<div class="p-5 grid grid-cols-1 gap-2">
	  				<div v-for="user in users">
	  					<div class="flex">
	  						<div class="rounded-full bg-green-500 h-1 mr-2 p-1"></div><p>{{user.first_name}}</p>
	  					</div>
	  				</div>
  				</div>
  				<div class="p-5 grid grid-cols-1 gap-2">
  					<button class="border-2 w-24 border-black rounded-full p-1">неделя</button><button class="border-2 w-24 border-black rounded-full p-1">месяц</button><button class="border-2  w-24 border-black rounded-full p-1">квартал</button><button class="border-2  w-24 border-black rounded-full p-1">год</button>
  				</div>
  			</div>
  		</div>
  		<div class="flex justify-start gap-2">
  			<div class="border-2 rounded-lg p-5 w-2/3">Рейтинг продаж услуг\товаров
  				<div class="flex justify-end">
  					<div class="p-5">
  						<table>
  							<tr>
  								<td>1&nbsp;&nbsp;</td>
  								<td>Наименование услуги/товара<div class="bg-indigo-500 h-1 w-full"></div></td>
  								
  							</tr>
  							<tr>
  								<td>2&nbsp;&nbsp;</td>
  								<td>Наименование услуги/товара<div class="bg-indigo-500 h-1 w-3/4"></div></td>
  							</tr>
  							<tr>
  								<td>3&nbsp;&nbsp;</td>
  								<td>Наименование услуги/товара<div class="bg-indigo-500 h-1 w-2/4"></div></td>
  							</tr>
  							<tr>
  								<td>4&nbsp;&nbsp;</td>
  								<td>Наименование услуги/товара<div class="bg-indigo-500 h-1 w-1/4"></div></td>
  							</tr>
  						</table>
  					</div>
  					<div class="grid grid-cols-1 gap-2 mt-4">
  						<button class="border-2 w-24 border-black rounded-full p-1">неделя</button>
  						<button class="border-2 w-24 border-black rounded-full p-1">месяц</button>
  						<button class="border-2  w-24 border-black rounded-full p-1">квартал</button>
  						<button class="border-2  w-24 border-black rounded-full p-1">год</button>
  					</div>
  				</div>
  			</div>
  			<div class="border-2 rounded-lg p-5 w-1/3">Выполнение плана ОП
  				<div>
  					<canvas id="myCanvas" width="200" height="200"></canvas>
  				</div>
  			</div>
  		</div>
  		
  	</div>
		
	</div>
</template>
<script>
import SalesChart from '@/Shared/SalesChart'
import ClientChart from '@/Shared/ClientChart'
import Layout from '@/Shared/Layout'


export default{
	layout: Layout,
	components:{
		SalesChart,
		ClientChart,
	},
	mounted(){
		function degreesToRadians(deg) {
        return (deg/180) * Math.PI;
      }

      function percentToRadians(percentage) {
        // convert the percentage into degrees
        var degrees = percentage * 360 / 100;
        // and so that arc begins at top of circle (not 90 degrees) we add 270 degrees
        return degreesToRadians(degrees + 270);
      }

      function drawPercentageCircle(percentage, radius, canvas) {
      var context = canvas.getContext('2d');

      var x = canvas.width / 2;
      var y = canvas.height / 2;
      var startAngle = percentToRadians(0);
      var endAngle = percentToRadians(percentage);
      // set to true so that we draw the missing percentage
      var counterClockwise = true;

      context.beginPath();
      context.arc(x, y, radius, startAngle, endAngle, counterClockwise);
      context.lineWidth = 15;

      // line color
      context.strokeStyle = 'lightyellow';
      context.stroke();

      // set to false so that we draw the actual percentage
      counterClockwise = false;

      context.beginPath();
      context.arc(x, y, radius, startAngle, endAngle, counterClockwise);
      context.lineWidth = 15;

      // line color
      context.strokeStyle = 'lightblue';
      context.stroke();

      // now draw the inner text
            context.font = radius/2.5 + "px Helvetica";
            context.fillStyle = "lightblue";
            context.textAlign = "center";
            // baseline correction for text
            context.fillText(percentage+"%", x, y*1.05); 
      }
     // implantation happens here

        var canvas = document.getElementById('myCanvas');
        var percentage = 70;
        var radius;
        if (myCanvas.height < myCanvas.width) {
            radius = myCanvas.height / 3;
        }
        else {
            radius = myCanvas.width / 3;
        }
      drawPercentageCircle(percentage, radius, canvas);


		console.log(document.getElementById("pie"));
		  var canvas = document.getElementById("pie");
		  var ctx = canvas.getContext("2d");

		  
		  var colors = ['#4CAF50', '#00BCD4', '#E91E63', '#FFC107', '#9E9E9E', '#CDDC39', '#18FFFF', '#F44336', '#FFF59D', '#6D4C41'];
		  var angles = [Math.PI * 0.3, Math.PI * 0.7, Math.PI * 0.2, Math.PI * 0.4, Math.PI * 0.4];
		  var offset = 10;
		  var beginAngle = 0;
		  var endAngle = 0;
		  var offsetX, offsetY, medianAngle;
		  
		  for(var i = 0; i < angles.length; i = i + 1) {
		    beginAngle = endAngle;
		    endAngle = endAngle + angles[i];
		    medianAngle = (endAngle + beginAngle) / 2;
		    offsetX = Math.cos(medianAngle) * offset;
		    offsetY = Math.sin(medianAngle) * offset;
		    ctx.beginPath();
		    ctx.fillStyle = colors[i % colors.length];
		    ctx.moveTo(100 + offsetX, 80 + offsetY);
		    ctx.arc(100 + offsetX, 80 + offsetY, 70, beginAngle, endAngle);
		    ctx.lineTo(100 + offsetX, 80 + offsetY);
		    ctx.stroke();
		    ctx.fill();
		  }
		
	},
	props:{
		users:Array
	}
}	

</script>