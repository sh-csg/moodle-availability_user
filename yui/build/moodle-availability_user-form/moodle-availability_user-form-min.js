YUI.add("moodle-availability_user-form",function(t,e){M.availability_user=M.availability_user||{},M.availability_user.form=t.Object(M.core_availability.plugin),M.availability_user.form.initInner=function(e){this.params=e,this.params.sort(function(e,a){return e.lastname.toLowerCase()<a.lastname.toLowerCase()||e.lastname.toLowerCase()===a.lastname.toLowerCase()&&e.firstname.toLowerCase()===a.firstname.toLowerCase()?-1:e.lastname.toLowerCase()===a.lastname.toLowerCase()&&e.firstname.toLowerCase()===a.firstname.toLowerCase()?0:1})},M.availability_user.form.getNode=function(e){var a,i='<label><span class="col-form-label pr-3">'+M.util.get_string("title","availability_user")+'</span><span class="availability-group form-group"><select class="custom-select">';return this.params.forEach(function(e){i+='<option value="'+e.id+'">'+e.lastname+", "+e.firstname+"</option>"}),i+="</select></span></label>",a=t.Node.create("<span>"+i+"</span>"),e.userid&&(null===a.one("option[value="+e.userid+"]")&&a.one("select").appendChild(t.Node.create('<option value="'+e.userid+'">('+M.util.get_string("missing_user","availability_user")+")")),a.one("option[value="+e.userid+"]").set("selected",!0)),M.availability_user.form.addedEvents||(M.availability_user.form.addedEvents=!0,t.one("#fitem_id_availabilityconditionsjson").delegate("click",function(){M.core_availability.form.update()},".availability_user select")),a},M.availability_user.form.fillValue=function(e,a){var i=a.one("select");e.userid=i.get("options").item(i.get("selectedIndex")).get("value")}},"@VERSION@",{requires:["base","node","event","moodle-core_availability-form"]});