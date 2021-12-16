const showCurrentTableColumnsCount = (table) => {
	let countContent,
		qpm = {
				command : 3,
				command : {
						subCMD: 'showTableColumns'
				},
				parameters: {
						attribute: table
				}
		};
	  
	 const requestOptions = {
        method: 'POST',
        body: {'svcQuery': JSON.stringify(qpm)}
	 };
	  
	 var r = fetch('/admin/api/dataServices/filters', requestOptions);
	 
	 if(r.ok()){
		let res = r.json(),
			count = res.response[0].columncount;
			
		if(count === 0){ countContent = <footer>Not filters</footer>; }
		if(count === 1){ countContent = <footer>{count} filter</footer>; }
		if(count > 1){ countContent = <footer>{count} filters</footer>; }
		
	 }
	
	 return countContent;
}

class Add extends React.Component{
  JQueryCall(){
	  	$('#add-attribute > button').click(addAttribute);
		$('#add-attribute > div input').change(formRealTime);
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
  constructor(){
	  super();
	  this.state = {
		  listSheet: null
	  };
  }
  JQueryCall(){ $('#attributes-list > .list-cont').click(redirectToAttribute); }
  componentDidMount(){
	  let qpm = {
		  command: 3,
		  command: {
			subCMD: 'showTables'
		  }
	  };
	  
	  const requestOptions = {
        method: 'POST',
        body: {'svcQuery': JSON.stringify(qpm)}
	  };
	  
	  fetch('/admin/api/dataServices/filters', requestOptions)
        .then(response => response.json())
        .then(data => this.setState({ listSheet: data }));
        
      this.JQueryCall();
  }
  render(){
	  
	let responseList = this.state.listSheet.response;
	const renderData = responseList.map(r => {
		return (
				<div className="list-cont">
					<header>{ r.Tables_in_Investportal }</header>
					{ showCurrentTableColumnsCount(r.Tables_in_Investportal) }
				</div>
		);
	});
	
    return (
      <React.Fragment>
        <section id="attributes-list">{renderData}</section>
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
	let jsonQuery = $('.add-fields > #queryParameters').val();
	var sendProccess = fetch('/admin/api/dataServices/filters', {
		method: 'POST',
		body: {'svcQuery': jsonQuery}
	});
	
	if(sendProccess.ok){ window.location.assign('/admin?svc=dataManagment&subSVC=attributes#list'); }
	else{ alert('Send error'); }
}

const formRealTime = (e,t) => {
	
	let q = {},
		r = "";
		
	var currentValue = $(this).val();
	
	q = {
		command: 0,
		command: {
			subCMD: "sendAttribute"
		},
		parameters: {
			attribute: currentValue,
			group: 'meta'
		}
	};
	
	r = JSON.stringify(q);
		
	
	
	jsonQueryConstructor('.add-fields > #queryParameters', r);
	
	e.preventDefault();
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
