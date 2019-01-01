<template>
	<transition name="fade">
        <div class="alert alert-success success-block"  v-if="success">
        	{{alertBox}}
            <p>
            	<slot></slot>
            </p>
        </div>
    </transition>
    <!-- <alert-box :success="success" v-on:alertBoxClose="success = !success">
            Данные успешно изменены
        </alert-box> -->
</template>

<script>
  export default {
  		props: ['success'],
  		
  		computed: {
            alertBox() {
            	var app = this;
            	setTimeout(
            		function() {
            			app.$emit('alertBoxClose');
            		}, 1500)
            		
            },
        }
  	}
</script>