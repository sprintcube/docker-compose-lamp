const showCurrentTableColumnsCount = (table) => {
	let countContent,
		qpm = {
				parameters: {
						attribute: table
				}
		};
		
	 var fd = new FormData();
	 fd.append('svcQuery', JSON.stringify(qpm));
	  
	 const requestOptions = {
        method: 'POST',
        body: fd
	 };
	  
	 var r = fetch('/admin/api/dataServices/filters/TableColumns/show', requestOptions);
	 
	 if(r.ok()){
		let res = r.json(),
			count = res[0].columncount;
			
		if(count === 0){ countContent = <footer>Not filters</footer>; }
		if(count === 1){ countContent = <footer>{count} filter</footer>; }
		if(count > 1){ countContent = <footer>{count} filters</footer>; }
		
	 }
	
	 return countContent;
}

class Add extends React.Component{
  JQueryCall(){
	  	$('#add-attribute > button').click(addAttribute);
		$('#add-attribute > div input').on('input', formRealTime);
  }
  componentDidMount(){ this.JQueryCall(); }
  render(){
    return (
      <React.Fragment>
        <div id="add-attribute">
		  <input type="hidden" id="queryParameters" value="" />
          <div>
            <h2>Please, input attribute name(only English)</h2>
            <input type="text" id="theme" />
          </div>
          <button>Insert attribute</button>
        </div>
      </React.Fragment>
    );
  }
}
class List extends React.Component{
  constructor(props){
	  super(props);
	  this.state = {
		  listSheet: null
	  };
  }
  JQueryCall(){ $('#attributes-list > .list-cont').click(redirectToAttribute); }
  componentDidMount(){
	  
	  const requestOptions = {
        method: 'GET'
	  };
	  
	  fetch('/admin/api/dataServices/filters/Attributes/show', requestOptions)
        .then(response => response.json())
        .then(data => this.setState({ listSheet: data }))
        .catch(error => {
			alert('Response error!');
			console.log(error);
		});
        
      this.JQueryCall();
  }
  render(){
    return (
      <React.Fragment>
        <section id="attributes-list">{
			this.state.listSheet.map(r => {
				return (
					<div className="list-cont">
						<header>{ r.Tables_in_Investportal }</header>
						{ showCurrentTableColumnsCount(r.Tables_in_Investportal) }
					</div>
				)
			})
		}</section>
      </React.Fragment>
    );
  }
}


const HeaderRender = (hash) => {
  let render = "";
  switch(hash){
    case "#add":
		render = '<a href="#list">Back to list</a>';
    break;
    default:  
        render = '<a href="#add">New attribute</a>';
    break;
  }
  
  $('.data-page > header nav').html(render);
}
document.title = "Data Attributes";
const UIRender = (hash) => {
  switch(hash){
    case "#add": document.title = "Add new Attribute"; break;
    default: document.title = "Data Attributes"; break;
  }
}
const UXRender = (hash) => {
  switch(hash){
    case "#add": ReactDOM.render(<Add />, document.querySelector('.data-page > main')); break;
    default: ReactDOM.render(<List />, document.querySelector('.data-page > main')); break;
  }
}


$('.data-page > header h2').html(document.title);

const jsonQueryConstructor = (query,pm) => {
	$(query).val(pm);
}

const addAttribute = (e,t) => {
	let jsonQuery = $('#add-attribute > #queryParameters').val();
	
	var fd = new FormData();
	fd.append('svcQuery', jsonQuery);
	
	
	fetch('/admin/api/dataServices/filters/Attribute/send', {
		method: 'POST',
		body: fd
	}).then(response => {
		if(response.status != 503){ window.location.assign('/admin?svc=dataManagment&subSVC=attributes#list'); }
		else{ alert('Response error!'); }
	}).catch(error => {
		alert('Response error!');
	});
}

const formRealTime = (e,t) => {
	
	let q = {},
		r = "";
		
	var currentValue = $('#add-attribute > div input#theme').val();
	
	q = {
		parameters: {
			attribute: currentValue,
			group: 'meta'
		}
	};
	
	r = JSON.stringify(q);
		
	
	
	jsonQueryConstructor('#add-attribute > #queryParameters', r);
}

const redirectToAttribute = (e,t) => {
	let currentAttribute = $('#attributes-list > .list-cont header').eq($(this).index()).text(),
		currentCount = $('#attributes-list > .list-cont footer').eq($(this).index()).text(),
		uri = currentAttribute.toLowerCase();
	
	
	if(!currentCount.indexOf('Not')){ window.location.assign('/admin?svc=dataManagment&subSVC=filters&attr=' + uri + '#edit'); }
	else{ window.location.assign('/admin?svc=dataManagment&subSVC=filters&attr=' + uri + '#add'); }
}

$(document).ready(function(){
	$(window).on('hashchange',function(){
		let s = window.location.hash;
		
		HeaderRender(s);
		UIRender(s);
		UXRender(s);
	}).trigger('hashchange');
});
