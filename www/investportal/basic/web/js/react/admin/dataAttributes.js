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
		  listSheet: []
	  };
	  this.redirectToAttribute = this.redirectToAttribute.bind(this);
  }
  redirectToAttribute(e,t){
	  let currentAttribute = $('#attributes-list > .list-cont header').eq($(this).index()).html(),
		  uri = currentAttribute.toLowerCase(),
		  dataState = '',
		  statusData = '';
		  
	  let qpm = {
		parameters: {
			attribute: uri
		}
	  };
		  
	  fetch('/admin/api/dataServices/filters/notEmptyAttribute/show', {method: 'POST', body: {'svcQuery': JSON.stringify(qpm)}})
        .then(response => response.json())
		.then(data => statusData = data.avabillityData)
		.catch(error => {
			alert('Generate error!');
			console.log(error);
		});
		
	  if(statusData === 0){ dataState = '#edit'; }
	  else{ dataState = '#add'; }
		
		  
	  window.location.assign('/admin?svc=dataManagment&subSVC=filters&attr=' + uri + dataState);
  }
  
  fetchData(svc,rq){
	  fetch(svc, rq)
        .then(response => response.json())
		.then(data => this.setState({ listSheet: data }))
		.catch(error => {
			alert('Response error!');
			console.log(error);
		});
  }
  componentDidMount(){
	  
	  const requestOptions = {
        method: 'GET'
	  };
	  
	  this.fetchData('/admin/api/dataServices/filters/Attributes/show', requestOptions);
  }
  render(){
	const myStates = this.state.listSheet.map((myState) => <div className="list-cont" onClick={this.redirectToAttribute}><header>{ myState }</header></div>);
	
    return (
      <React.Fragment>
        <section id="attributes-list">{myStates}</section>
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
		if(response.status != 503 && response.status != 404){ window.location.assign('/admin?svc=dataManagment&subSVC=attributes#list'); }
		else{ alert('Send error!'); }
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

$(document).ready(function(){
	$(window).on('hashchange',function(){
		let s = window.location.hash;
		
		HeaderRender(s);
		UIRender(s);
		UXRender(s);
	}).trigger('hashchange');
});
