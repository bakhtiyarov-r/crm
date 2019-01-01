// resources/assets/js/views/Company.vue
<template>
	<div>
		<section>
			<div class="container">
				<div class="row">
					<div class="col-lg-1">
						<div class="title">
							<h2>Компания</h2>
						</div>
					</div>
				</div>
				<div class="row divider">
					<div class="col-md-9 col-lg-10">
						<div class="status">
							<span class="status_item">Общие сведения</span>
							<a href="#users" v-on:click="currentTab = 'company-users'" v-bind:class="['status_item', { active: currentTab === 'company-users' }]">Люди</a>
							<span class="status_item">Документы <span class="status_count" >0</span></span>
						</div>
					</div>
				</div>
			</div>		
		</section>
        <keep-alive>
		    <component
		      v-bind:is="currentTabComponent"
		      class="tab"
		    ></component>
		</keep-alive>
	</div>
</template>
<script>
	import CompanyUsers from './CompanyUsers'
  export default {
  	data() {
  		return {
  			currentTab: 'company-users',
            error: false,
            errors: {},
  		}
  	},
  	components: {
	    'company-users': CompanyUsers
	},
    computed: {
    	currentTabComponent () {
	      return this.currentTab
	    }
    }
  }
</script>

