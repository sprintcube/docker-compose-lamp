var params = window
    .location
    .search
    .replace('?','')
    .split('&')
    .reduce(
        function(p,e){
            var a = e.split('=');
            p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
            return p;
        },
        {}
    );

function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

class List extends React.Component{
  constructor(){
	  super();
	  this.state = {
		  listSheet: []
	  };
  }
  JQueryCall(){
	  let elService = [$('.filters-list > main #filters-card #footer nav span:nth-last-child(1)'),$('.filters-list > main #filters-card #footer nav span:nth-last-child(2)')],
		  eventService = [deleteFilters,redirectToDataForm];

	  for(let i = 0; i < eventService.length; i++){ elService[i].click(eventService[i]); }
  }
  componentDidMount(){
	  
	  const requestOptions = {
        method: 'GET'
	  };
	  
	  fetch('/admin/api/dataServices/filters/Attributes/show', requestOptions)
        .then(response => response.json())
        .then(data => this.setState({ listSheet: data }));
        
      this.JQueryCall();
  }
  render(){
	let responseList = this.state.listSheet;
	const renderData = responseList.map((myState) => {
		return (
				<div id="filter-card">
					<div id="header">{myState}</div>
					<div id="main">{RenderAttributeFiltersList('List', myState)}</div>
					<div id="footer">{RenderAttributeFiltersList('Control', myState)}</div>
				</div>
		);
	});
	
    return (
      <React.Fragment>
        <section id="filters-list">
          <header><h2>Attributes filters list</h2></header>
          <main>{renderData}</main>
        </section>
      </React.Fragment>
    );
  }
}
class Add extends React.Component{
  JQueryCall(){
		let elService = $('.add-fields > footer button'),
			eventService = addFilters;

	    elService.click(eventService);
	    
		$('.add-fields > main select').on('change', selectFilterType);
		
		sessionStorage.setItem('currentAttr', capitalizeFirstLetter(params['attr']));
  }
  componentDidMount(){ this.JQueryCall(); }
  render(){
    return (
      <React.Fragment>
        <div className="add-fields">
		  <input type="hidden" id="queryParameters" value="" />
          <header><h2>Add current attribute filters group</h2></header>
          <main>
            <div>
              <input type="text" name="field" id="field" placeholder="Enter the field name" />
              <select name="fieldType" id="fieldType">
                <option>Select form field type</option>
                <option value="default">Data field</option>
                <option value="int">Integer field</option>
                <option value="precentable">Precentable field</option>
                <option value="cost">Cost field</option>
                <option value="smartDatasets">Smart Datasets</option>
                <option value="photogallery">Photogallery</option>
              </select>
            </div>
          </main>
          <footer><button>Add field</button></footer></div>
          <ul className="add-modals">
			<li>
				<section className="form-rpa-addon">
				  <header>
					<h2>Smart Datasets Window</h2>
					<div id="header-right">
					  <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABmJLR0QA/wD/AP+gvaeTAAAApElEQVRYhe2WUQqAIAxAt+iU3SY6dsH6cSDamEtxBXtfaanP6TSAICigPi4i2jwFzBKr9AIRsWsmjSwzBukSmL4nuOUgAbuEJCCVn+qzwXezxEiB9GyTeCug9HGkqrP8tko1bshpKJVbyFO57IdxT0PxIJIYfUC5R8BdwLwElk0IoC/Z/yLAaDNrjZR7BEKg2gOz/oQY9wios7XmvTrg1y6jILgBISbRIFShha8AAAAASUVORK5CYII=" data-operation="Save" />
					  <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABmJLR0QA/wD/AP+gvaeTAAAB40lEQVRYhe2WzU4TURTH/+PWLpWotZGvWN+FUrZin6BQFwRehJ0YHoBUSoJRH4QuGje6EPZ8rEqpPxZzJh3Knem9A0STcpImzT3n/3HPzNx7pMeY9ohCioGKpBVJS5JmJVUs9UfSb0nfJR1GUXR8nyYFlIEdYMDkGAJt4M19ideBcyPvA3vAe6AKPLVfFVi1XN9qz4Hlu4p/tB1hu5rzwMwD+6lutIqK143gCtgogN807DC4E/bMk7YHi4+ZADgDXoYAd5O2FxVPcXWMa8cXULHW9V3PHGgCM471GaDpWF8wrgFQ9jHQMsd7jtya5bppEybetdyaA9fOyrkM/LDi1YxddtMmXGsOXMPy33wM/LTitxn5ccFcccNUrabnY+DCiks5Nc+BI0bRA17k1Jes7mI898RVP9GlO/LulUTn70SW/+ER/POXcN2KH+IzvHVOuAy8Jj40+sC8Ix96EC0SchAZ6LM53vcC5HMdGNenENArRpfR5h3Et4zjNO8zzQIvM7qOg02YeHIdL4XiE5IWo4GkAyx4YBZTbR8C64XEU4Q14vsce5nawAfgHfEJV7L/DeALcJlqe7GdO0w8A7bxG0oHxAOs1wASOpaXJdUl1STN6eZY/kvxWP41iqKTEN7HmO64BtsZlPR175QhAAAAAElFTkSuQmCC" data-operation="Close" />
					</div>
				  </header>
				  <main>
					<div id="smdf-header">
					  <nav>
						<a href="" className="active">Single mode</a>
						<a href="">Search mode</a>
					  </nav>
					</div>
					<div id="smdf-content">
					  <section><header>
						<h3>Select form "element":</h3>
						<div><input type="radio" id="element" value="list" />List</div>
						<div><input type="radio" id="element" value="input" />Input field</div>
					  </header>
					  <main>
						<h3>Input your need dataset:</h3>
						<label htmlFor="dataset">Add dataset</label>
						<input type="file" id="dataset" accept="application/xml, application/json, application/vnd.ms-excel" />
						<div className="downloaded-file"></div>
					  </main></section>
					  <section className="mode-hidden"><header>
						<h3>Select form elements:</h3>
						<div><input type="radio" id="element" value="list" />List's</div>
						<div><input type="radio" id="element" value="input" />Input fields</div>
					  </header>
					  <main>
						 <h3>Input your priority dataset:</h3>
						<label htmlFor="dataset">Add dataset</label>
						<input type="file" id="dataset" accept="application/xml, application/json, application/vnd.ms-excel" />
						<div className="downloaded-file"></div>
					  </main>
					  <footer>
						 <h3>Add datasets in search group:</h3>
						<label htmlFor="datasets">Add datasets</label>
						<input type="file" id="datasets" accept="application/xml, application/json, application/vnd.ms-excel" multiple/>
						<div className="downloaded-files"><ul></ul></div>
					  </footer></section>
					</div>
				  </main>
				</section>
			</li>
			<li>
				<section className="form-rpa-addon">
				  <header>
					<h2>Photogallery Window</h2>
					<div id="header-right">
					  <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABmJLR0QA/wD/AP+gvaeTAAAApElEQVRYhe2WUQqAIAxAt+iU3SY6dsH6cSDamEtxBXtfaanP6TSAICigPi4i2jwFzBKr9AIRsWsmjSwzBukSmL4nuOUgAbuEJCCVn+qzwXezxEiB9GyTeCug9HGkqrP8tko1bshpKJVbyFO57IdxT0PxIJIYfUC5R8BdwLwElk0IoC/Z/yLAaDNrjZR7BEKg2gOz/oQY9wios7XmvTrg1y6jILgBISbRIFShha8AAAAASUVORK5CYII=" data-operation="Save" />
					  <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABmJLR0QA/wD/AP+gvaeTAAAB40lEQVRYhe2WzU4TURTH/+PWLpWotZGvWN+FUrZin6BQFwRehJ0YHoBUSoJRH4QuGje6EPZ8rEqpPxZzJh3Knem9A0STcpImzT3n/3HPzNx7pMeY9ohCioGKpBVJS5JmJVUs9UfSb0nfJR1GUXR8nyYFlIEdYMDkGAJt4M19ideBcyPvA3vAe6AKPLVfFVi1XN9qz4Hlu4p/tB1hu5rzwMwD+6lutIqK143gCtgogN807DC4E/bMk7YHi4+ZADgDXoYAd5O2FxVPcXWMa8cXULHW9V3PHGgCM471GaDpWF8wrgFQ9jHQMsd7jtya5bppEybetdyaA9fOyrkM/LDi1YxddtMmXGsOXMPy33wM/LTitxn5ccFcccNUrabnY+DCiks5Nc+BI0bRA17k1Jes7mI898RVP9GlO/LulUTn70SW/+ER/POXcN2KH+IzvHVOuAy8Jj40+sC8Ix96EC0SchAZ6LM53vcC5HMdGNenENArRpfR5h3Et4zjNO8zzQIvM7qOg02YeHIdL4XiE5IWo4GkAyx4YBZTbR8C64XEU4Q14vsce5nawAfgHfEJV7L/DeALcJlqe7GdO0w8A7bxG0oHxAOs1wASOpaXJdUl1STN6eZY/kvxWP41iqKTEN7HmO64BtsZlPR175QhAAAAAElFTkSuQmCC" data-operation="Close" />
					</div>
				  </header>
				  <main>
					  <ul id="pg">
						<li>
						  <span>Select the image formats on which file downloads will be available the current "attribute":</span>
						  <div>
							<input type="checkbox" name="format" id="format" value="jpeg" />
							<span>JPEG</span>
						  </div>
						  <div>
							<input type="checkbox" name="format" id="format" value="png" />
							<span>PNG</span>
						  </div>
						  <div>
							<input type="checkbox" name="format" id="format" value="webp" />
							<span>WEBP</span>
						  </div>
						  <div>
							<input type="checkbox" name="format" id="format" value="vnd.ms-photo" />
							<span>JPEG-XR</span>
						  </div>
						</li>
						<li>
						  <span>Enter the maximum number of uploaded photos on the gallery:</span>
						  <div>
							<input type="number" id="imagesCount" name="imagesCount" value="4" />
						  </div>
						</li>
						
					  </ul>
				  </main>
				</section>
			</li>
          </ul>
      </React.Fragment>
    );
  }
}
class Edit extends React.Component{
  constructor(){
	 super(); 
	 this.state = {
		  currentAttributeSheet: []
	 };
  }
  JQueryCall(){
	  let elService = $('.edit-fields > footer button'),
		  eventService = updateFilters;

	  elService[i].click(eventService[i]);
	  
	  $('.edit-fields > main select').on('change', selectFilterType);
	  
	  sessionStorage.setItem('currentAttr', capitalizeFirstLetter(params['attr']));
  }
  componentDidMount(){
	  let qpm = {
		  parameters: {
			"attribute": params["attr"]
		  }
	  };
	  
	  const requestOptions = {
        method: 'POST',
        body: {'svcQuery': JSON.stringify(qpm)}
	  };
	  
	  fetch('/admin/api/dataServices/filters/Filters/show', requestOptions)
        .then(response => response.json())
        .then(data => this.setState({ currentAttributeSheet: data }));
        
     this.JQueryCall();
  }
  render(){
	let responseList = this.state.listSheet;
	
	if(responseList){
		const parametersRender = responseList.map((myState) => {
				<div>
					<input type="text" name="field" id="field" value={ myState.field } />
					<select name="fieldType" id="fieldType">
						<option>Select form field type</option>
						<option value="default">Data field</option>
						<option value="int">Integer field</option>
						<option value="precentable">Precentable field</option>
						<option value="cost">Cost field</option>
						<option value="smartDatasets">Smart Datasets</option>
						<option value="photogallery">Photogallery</option>
					</select>
				</div>
		});
	}
	else{
		window.location.assign('/admin?svc=dataManagment&subSVC=filters&attr=' + params['attr'] + '#add');
	}
	
    return (
      <React.Fragment>
        <div className="edit-fields">
		  <input type="hidden" id="queryParameters" value="" />
          <header><h2>Edit current filters group for attribute</h2></header>
          <main>{parametersRender}</main>
          <footer><button>Add field</button></footer></div>
          <ul className="edit-modals">
			<li>
				<section className="form-rpa-addon">
				  <header>
					<h2>Smart Datasets Window</h2>
					<div id="header-right">
					  <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABmJLR0QA/wD/AP+gvaeTAAAApElEQVRYhe2WUQqAIAxAt+iU3SY6dsH6cSDamEtxBXtfaanP6TSAICigPi4i2jwFzBKr9AIRsWsmjSwzBukSmL4nuOUgAbuEJCCVn+qzwXezxEiB9GyTeCug9HGkqrP8tko1bshpKJVbyFO57IdxT0PxIJIYfUC5R8BdwLwElk0IoC/Z/yLAaDNrjZR7BEKg2gOz/oQY9wios7XmvTrg1y6jILgBISbRIFShha8AAAAASUVORK5CYII=" data-operation="Save" />
					  <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABmJLR0QA/wD/AP+gvaeTAAAB40lEQVRYhe2WzU4TURTH/+PWLpWotZGvWN+FUrZin6BQFwRehJ0YHoBUSoJRH4QuGje6EPZ8rEqpPxZzJh3Knem9A0STcpImzT3n/3HPzNx7pMeY9ohCioGKpBVJS5JmJVUs9UfSb0nfJR1GUXR8nyYFlIEdYMDkGAJt4M19ideBcyPvA3vAe6AKPLVfFVi1XN9qz4Hlu4p/tB1hu5rzwMwD+6lutIqK143gCtgogN807DC4E/bMk7YHi4+ZADgDXoYAd5O2FxVPcXWMa8cXULHW9V3PHGgCM471GaDpWF8wrgFQ9jHQMsd7jtya5bppEybetdyaA9fOyrkM/LDi1YxddtMmXGsOXMPy33wM/LTitxn5ccFcccNUrabnY+DCiks5Nc+BI0bRA17k1Jes7mI898RVP9GlO/LulUTn70SW/+ER/POXcN2KH+IzvHVOuAy8Jj40+sC8Ix96EC0SchAZ6LM53vcC5HMdGNenENArRpfR5h3Et4zjNO8zzQIvM7qOg02YeHIdL4XiE5IWo4GkAyx4YBZTbR8C64XEU4Q14vsce5nawAfgHfEJV7L/DeALcJlqe7GdO0w8A7bxG0oHxAOs1wASOpaXJdUl1STN6eZY/kvxWP41iqKTEN7HmO64BtsZlPR175QhAAAAAElFTkSuQmCC" data-operation="Close" />
					</div>
				  </header>
				  <main>
					<div id="smdf-header">
					  <nav>
						<a href="" className="active">Single mode</a>
						<a href="">Search mode</a>
					  </nav>
					</div>
					<div id="smdf-content">
					  <section><header>
						<h3>Select form "element":</h3>
						<div><input type="radio" id="element" value="list" />List</div>
						<div><input type="radio" id="element" value="input" />Input field</div>
					  </header>
					  <main>
						<h3>Update your need dataset:</h3>
						<label htmlFor="dataset">Add dataset</label>
						<input type="file" id="dataset" accept="application/xml, application/json, application/vnd.ms-excel" />
						<div className="downloaded-file"></div>
					  </main></section>
					  <section className="mode-hidden"><header>
						<h3>Select form elements:</h3>
						<div><input type="radio" id="element" value="list" />List's</div>
						<div><input type="radio" id="element" value="input" />Input fields</div>
					  </header>
					  <main>
						 <h3>Update your priority dataset:</h3>
						<label htmlFor="dataset">Add dataset</label>
						<input type="file" id="dataset" accept="application/xml, application/json, application/vnd.ms-excel" />
						<div className="downloaded-file"></div>
					  </main>
					  <footer>
						 <h3>Update datasets the search group:</h3>
						<label htmlFor="datasets">Add datasets</label>
						<input type="file" id="datasets" accept="application/xml, application/json, application/vnd.ms-excel" multiple/>
						<div className="downloaded-files"><ul></ul></div>
					  </footer></section>
					</div>
				  </main>
				</section>
			</li>
			<li>
				<section className="form-rpa-addon">
				  <header>
					<h2>Photogallery Window</h2>
					<div id="header-right">
					  <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABmJLR0QA/wD/AP+gvaeTAAAApElEQVRYhe2WUQqAIAxAt+iU3SY6dsH6cSDamEtxBXtfaanP6TSAICigPi4i2jwFzBKr9AIRsWsmjSwzBukSmL4nuOUgAbuEJCCVn+qzwXezxEiB9GyTeCug9HGkqrP8tko1bshpKJVbyFO57IdxT0PxIJIYfUC5R8BdwLwElk0IoC/Z/yLAaDNrjZR7BEKg2gOz/oQY9wios7XmvTrg1y6jILgBISbRIFShha8AAAAASUVORK5CYII=" data-operation="Save" />
					  <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABmJLR0QA/wD/AP+gvaeTAAAB40lEQVRYhe2WzU4TURTH/+PWLpWotZGvWN+FUrZin6BQFwRehJ0YHoBUSoJRH4QuGje6EPZ8rEqpPxZzJh3Knem9A0STcpImzT3n/3HPzNx7pMeY9ohCioGKpBVJS5JmJVUs9UfSb0nfJR1GUXR8nyYFlIEdYMDkGAJt4M19ideBcyPvA3vAe6AKPLVfFVi1XN9qz4Hlu4p/tB1hu5rzwMwD+6lutIqK143gCtgogN807DC4E/bMk7YHi4+ZADgDXoYAd5O2FxVPcXWMa8cXULHW9V3PHGgCM471GaDpWF8wrgFQ9jHQMsd7jtya5bppEybetdyaA9fOyrkM/LDi1YxddtMmXGsOXMPy33wM/LTitxn5ccFcccNUrabnY+DCiks5Nc+BI0bRA17k1Jes7mI898RVP9GlO/LulUTn70SW/+ER/POXcN2KH+IzvHVOuAy8Jj40+sC8Ix96EC0SchAZ6LM53vcC5HMdGNenENArRpfR5h3Et4zjNO8zzQIvM7qOg02YeHIdL4XiE5IWo4GkAyx4YBZTbR8C64XEU4Q14vsce5nawAfgHfEJV7L/DeALcJlqe7GdO0w8A7bxG0oHxAOs1wASOpaXJdUl1STN6eZY/kvxWP41iqKTEN7HmO64BtsZlPR175QhAAAAAElFTkSuQmCC" data-operation="Close" />
					</div>
				  </header>
				  <main>
					  <ul id="pg">
						<li>
						  <span>Select the image formats in which file downloads will be available in current "attribute":</span>
						  <div>
							<input type="checkbox" name="format" id="format" value="jpeg" />
							<span>JPEG</span>
						  </div>
						  <div>
							<input type="checkbox" name="format" id="format" value="png" />
							<span>PNG</span>
						  </div>
						  <div>
							<input type="checkbox" name="format" id="format" value="webp" />
							<span>WEBP</span>
						  </div>
						  <div>
							<input type="checkbox" name="format" id="format" value="vnd.ms-photo" />
							<span>JPEG-XR</span>
						  </div>
						</li>
						<li>
						  <span>Enter the maximum number of uploaded photos in the gallery:</span>
						  <div>
							<input type="number" id="imagesCount" name="imagesCount" value="4" />
						  </div>
						</li>
						
					  </ul>
				  </main>
				</section>
			</li>
          </ul>
      </React.Fragment>
    );
  }
}

const HeaderRender = (hash) => {
  let render = '';
  switch(hash){
    case "#add":
		render = '<a href="#list">Back to list</a>';
    break;
    default:  
        render = '<a href="#add">New filter</a>';
    break;
  }
  
   $('.data-page > header nav').html(render);
}
document.title = "Data Filters";
const UIRender = (hash) => {
  switch(hash){
    case "#add": document.title = "Add new Filter"; break;
    case "#edit": document.title = "Edit current Filter"; break;
    default: document.title = "Data Filters"; break;
  }
}
const UXRender = (hash) => {
  switch(hash){
    case "#add": ReactDOM.render(<Add />, document.querySelector('.data-page > main')); break;
    case "#edit": ReactDOM.render(<Edit />, document.querySelector('.data-page > main')); break;
    default: ReactDOM.render(<List />, document.querySelector('.data-page > main')); break;
  }
}


$('.data-page > header h2').html(document.title);


const AddField = () => {
  $('.add-fields > main').append('<div>\n\t<input type="text" name="field" id="field" placeholder="Enter the field name" />\n\t<select name="fieldType" id="fieldType"><option>Select form field type</option><option value="defaultField">Data field</option><option value="intField">Integer field</option><option value="precentableField">Precentable field</option><option value="costField">Cost field</option><option value="smartDatasets">Smart Datasets</option><option value="photogalleryField">Photogallery</option></select></div>');
}

const jsonQueryConstructor = (query,pm) => {
	let isAdd = document.location.href.indexOf("add") ? true : null,
		isEdit = document.location.href.indexOf("edit") ? true : null;
		
	let result, endpoint;
	
	if(isAdd){
		if(sessionStorage.getItem('isADS')){
			let res;
			
			if(q.dataConstructor.group && q.dataConstructor.priority){
				res = query.dataConstructor.priority.concat(q.dataConstructor.group); 
			}
			else{ 
				res = query.dataConstructor.single; 
			}
			
			result = {
				parameters: {
					attribute: sessionStorage.getItem('currentAttr'),
					field: sessionStorage.getItem('currentField'),
					res
				}
			};
			endpoint = '/admin/api/dataServices/filters/Datasets/send';
	    }
	    else if(sessionStorage.getItem('isAPG')){
			result = {
				query,
				parameters: {
					attribute: sessionStorage.getItem('currentAttr'),
					field: sessionStorage.getItem('currentField')
				}
			};
			
			endpoint = '/admin/api/dataServices/filters/Photogallery/send';
	    }
	    else{
			result = {
				parameters: {
					attribute: sessionStorage.getItem('currentAttr'),
					field: sessionStorage.getItem('currentField'),
					type: query.type
				}
			};
			
			endpoint = '/admin/api/dataServices/filters/Filters/send';
		}
	}
	else if(isEdit){
		if(sessionStorage.getItem('isEDS')){
			let res;
			
			if(q.dataConstructor.group && q.dataConstructor.priority){
				res = query.dataConstructor.priority.concat(q.dataConstructor.group); 
			}
			else{ 
				res = query.dataConstructor.single; 
			}
			
			result = {
				parameters: {
					attribute: sessionStorage.getItem('currentAttr'),
					field: sessionStorage.getItem('currentField'),
					res
				}
			};
			endpoint = '/admin/api/dataServices/filters/Datasets/update';
	    }
	    else if(sessionStorage.getItem('isEPG')){
			result = {
				query,
				parameters: {
					attribute: sessionStorage.getItem('currentAttr'),
					field: sessionStorage.getItem('currentField')
				}
			};
			
			endpoint = '/admin/api/dataServices/filters/Photogallery/update';
	    }
	    else{
			result = {
				parameters: {
					attribute: sessionStorage.getItem('currentAttr'),
					field: sessionStorage.getItem('currentField'),
					type: query.type
				}
			};
			
			endpoint = '/admin/api/dataServices/filters/Filters/update';
		}
	}
	
	sessionStorage.setItem('ep', endpoint);
	$(pm).val(JSON.stringify(result));
}

const dsUploader = (e) => {
				let formatIcon = [
					'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'
				];

				var downloadsDatasets = $(this).prop('files');
				let isMultiple = downloadsDatasets.length !== 1 ? true : null;
				var ext = "";
				var responseLoad = "";
				
				if(isEdit){
				
					switch(isMultiple){
						case null:

							var file = downloadsDatasets[0];
							var parts = file.name.split(".");

							if (parts.length > 1){ ext = parts.pop(); }

							if(ext === 'csv'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[0] + '" />'; }
							if(ext === 'json'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[1] + '" />'; }
							if(ext === 'xml'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[2] + '" />'; }
							if(ext === 'xlsx'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[3] + '" />'; }

							responseLoad += '\n\t<span>' + file.name + '</span>\n</li>';

							$('.downloaded-files > ul').append(responseLoad);
							
							sessionStorage.setItem('dsue', uploadDataset(downloadedDatasets[0]));
							
							
						break;
						default:
							for(let i = 0; i < downloadedDatasets.length; i++){
								var file = downloadsDatasets[i];
								var parts = file.name.split(".");

								if (parts.length > 1){ ext = parts.pop(); }

								if(ext === 'csv'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[0] + '" />'; }
								if(ext === 'json'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[1] + '" />'; }
								if(ext === 'xml'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[2] + '" />'; }
								if(ext === 'xlsx'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[3] + '" />'; }

									responseLoad += '\n\t<span>' + file.name + '</span>\n</li>';
							}

							$('.downloaded-files > ul').append(responseLoad);
							sessionStorage.setItem('dsue', uploadMultipleDatasets(downloadedDatasets));
							
						break;
					}
				}
				else{
					switch(isMultiple){
						case null:

							var file = downloadsDatasets[0];
							var parts = file.name.split(".");

							if (parts.length > 1){ ext = parts.pop(); }

							if(ext === 'csv'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[0] + '" />'; }
							if(ext === 'json'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[1] + '" />'; }
							if(ext === 'xml'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[2] + '" />'; }
							if(ext === 'xlsx'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[3] + '" />'; }

							responseLoad += '\n\t<span>' + file.name + '</span>\n</li>';

							$('.downloaded-files > ul').append(responseLoad);
							sessionStorage.setItem('dsua', uploadDataset(downloadedDatasets[0]));
							
							
						break;
						default:
							for(let i = 0; i < downloadedDatasets.length; i++){
								var file = downloadsDatasets[i];
								var parts = file.name.split(".");

								if (parts.length > 1){ ext = parts.pop(); }

								if(ext === 'csv'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[0] + '" />'; }
								if(ext === 'json'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[1] + '" />'; }
								if(ext === 'xml'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[2] + '" />'; }
								if(ext === 'xlsx'){ responseLoad += '\n<li>\n\t<img src="data:image/png;base64,' + formatIcon[3] + '" />'; }

								responseLoad += '\n\t<span>' + file.name + '</span>\n</li>';

							}

							$('.downloaded-files > ul').append(responseLoad);
							sessionStorage.setItem('dsua', uploadMultipleDatasets(downloadedDatasets));
						break;
					}
				}
}

const dsSaver = (e,t) => {
				let isAdd = document.location.href.indexOf("add") ? true : null,
					isEdit = document.location.href.indexOf("edit") ? true : null;
					
				if(isEdit){
					if(validSmartField('dataset',sessionStorage.getItem('dsue')) === 'editDataReady'){
						sessionStorage.setItem('isEDS', true);
						jsonQueryConstructor(sessionStorage.getItem('dsue'), '#queryParameters');
						modalUI.eq(0).addClass('modal-close');
					}
					else{
						window.alert('Some required data for editing this attribute has not been loaded');
					}
				}
				else{
					if(validSmartField('dataset',sessionStorage.getItem('dsua')) === 'addDataReady'){
						sessionStorage.setItem('isADS', true);
						jsonQueryConstructor(sessionStorage.getItem('dsua'), '#queryParameters');
						modalUI.eq(0).addClass('modal-close');
					}
					else{
						window.alert('Some required data for added this attribute has not been loaded');
					}
				}
}


const pgUploader = (e,t) => {
				let isAdd = document.location.href.indexOf("add") ? true : null,
					isEdit = document.location.href.indexOf("edit") ? true : null;
				
				let constructorData = [];
				
				let addState, editState;

				var checkedFormats = modalUI.eq(1).find('.form-rpa-addon > main #pg li div #format:checked'),
					photosCount = modalUI.eq(1).find('.form-rpa-addon > main #pg li div #imagesCount');


				switch(isEdit){
					case null:
						let editOut = [];
						
						for(let i = 0; i < checkedFormats.length; i++){ editOut.push(checkedFormats.eq(i).val()); }

						constructorData.push({
							'value' : editOut,
							'format' : ParseInt(photosCount.val())
						});
						editState = constructorData;
					break;
					default:
						let addOut = [];

						for(let i = 0; i < checkedFormats.length; i++){ addOut.push(checkedFormats.eq(i).val()); }

						constructorData.push({
							'format' : addOut,
							'galleryCount' : ParseInt(photosCount.val())
						});
						
						addState = constructorData;
					break;
				}
				
				if(isEdit){
					if(validSmartField('photogallery',editState) === 'editDataReady'){
						sessionStorage.setItem('isEPG', true);
						jsonQueryConstructor(editState, '#queryParameters');
						modalUI.eq(1).addClass('modal-close');
					}
					else{
						window.alert('You have allowed inappropriate values for editing filters. Check them out and fix them!');
					}
				}
				else{
					if(validSmartField('photogallery',addState) === 'addDataReady'){
						sessionStorage.setItem('isAPG', true);
						jsonQueryConstructor(addState, '#queryParameters');
						modalUI.eq(1).addClass('modal-close');
					}
					else{
						window.alert('You have allowed inappropriate values for adding filters. Check them out and fix them!');
					}
				}
}


const openModal = (dataType) => {
	let isAdd = document.location.href.indexOf("add") ? true : null,
		isEdit = document.location.href.indexOf("edit") ? true : null,
		modalUI;
	
	switch(dataType){
		case 'smartDatasets':
			if(isEdit){ modalUI = $('.edit-modals > li'); }
			if(isAdd){ modalUI = $('.add-modals > li'); }
			
			modalUI.eq(0).removeClass('modal-close');
			
			$('.form-rpa-addon > main #smdf-content section * input[type=file]').change(dsUploader);
			$('#header-right > img').eq(0).click(dsSaver);
		break;
		case 'photogallery':
			if(isEdit){ modalUI = $('.edit-modals > li'); }
			if(isAdd){ modalUI = $('.add-modals > li'); }

			modalUI.eq(1).removeClass('modal-close');

			$('#header-right > img').eq(0).click(pgUploader);
		break;
		default:
			let formatQuery = { type: dataType };
			jsonQueryConstructor(formatQuery, '#queryParameters');
		break;
	}
}




const addFilters = (e,t) => {
	let jsonQuery = $('.add-filters > #queryParameters').val();
	
	if(jsonQuery !== '' || jsonQuery !== '{}' || jsonQuery !== '[]'){
		var sendProccess = fetch(sessionStorage.getItem('ep'), {
			method: 'POST',
			body: {'svcQuery': jsonQuery}
		});
		
		if(sendProccess.ok){ 
			$('.add-filters > #queryParameters').val('');
			AddField();
		}
		else{
			var sendError = alert('Connection to the service failed to perform this operation! Try again;-)');
			if(!sendError){ $('.add-fields > footer button').trigger('click'); }
		}
	}
	else{ alert('You have not selected the filters suggested by the list!'); }
	
}
const editFilters = (e,t) => {
	let jsonQuery = $('.edit-filters > #queryParameters').val();

	if(jsonQuery !== '' || jsonQuery !== '{}' || jsonQuery !== '[]'){
		var sendProccess = fetch(sessionStorage.getItem('ep'), {
			method: 'POST',
			body: {'svcQuery': jsonQuery}
		});
		
		if(sendProccess.ok){ 
			$('.edit-filters > #queryParameters').val('');
			AddField();
		}
		else{
			var sendError = alert('Connection to the service failed to perform this operation! Try again;-)');
			if(!sendError){ $('.edit-fields > footer button').trigger('click'); }
		}
	}
	else{ alert('You have not selected the filters suggested by the list!'); }
	
}
const deleteFilters = (e,t) => {
	let jsonQuery = {
		"parameters": {
			"attribute": $('#filters-list > #filter-card header').eq($(this).index()).text()
		}
	};

	
	var sendProccess = fetch('/admin/api/dataServices/filters/Attribute/delete', {
		method: 'POST',
		body: {'svcQuery': JSON.stringify(jsonQuery)}
	});
	
	if(sendProccess.ok){ window.reload(true); }
	else{
		var sendError = alert('Connection to the service failed to perform this operation! Try again;-)');
		if(!sendError){ $('.filters-list > main #filters-card #footer nav span:nth-last-child(1)').trigger('click'); }
	}
	
}

const redirectToDataForm = (e,t) => {
	let currentLink = $(this).text(),
					  redirect;
					  
					  
	switch(currentLink){
		case 'Add': redirect = '/admin?svc=dataManagment&subSVC=filters&attr='+ $(this).prev().prev().prev().prev().prev().prev().text() +'#add'; break;
		case 'Edit': redirect = '/admin?svc=dataManagment&subSVC=filters&attr='+ $(this).prev().prev().prev().text() +'#edit'; break;
	}
					  
					  
	window.location.assign(redirect);
}

const uploadDataset = (dataset) => {
	//При одиночном режиме

	let queryFile = { "dataConstructor": { "single": [] } },
		fileData = '';
	const fsAPI = new FileReader();

	fsAPI.onloadend = () => { fileData = fsAPI.result; };
	fsAPI.readAsDataURL(dataset);

	queryFile.dataConstructor.single.push({
		"file": fileData
	});

	return queryFile;

	
}
const uploadMultipleDatasets = (type, dsq) => {
	//При поисковом режиме

	let queryFile = { "dataConstructor":  [] },
		fileData;
	const fsAPI = new FileReader();
	
	switch(type){
		case 'priority': queryFile.dataConstructor.push({ priority: {} }); break;
		case 'group': queryFile.dataConstructor.push({ group: {} }); break;
	}

	if(type === 'priority'){
		fileData = '';

		fsAPI.onloadend = () => { fileData = fsAPI.result; };
		fsAPI.readAsDataURL(dsq);

		queryFile.dataConstructor.priority.push({
			smartDS: fileData
		});
	}
	if(type === 'group'){
		fileData = [];

		for(let i = 0; i < dsq.length; i++){
			fsAPI.onloadend = () => { fileData[i] = fsAPI.result; };
			fsAPI.readAsDataURL(dsq[i]);
		}

		queryFile.dataConstructor.group.push({
			isSmartDS: true,
			smartDS: fileData
		});
		
	}

	return queryFile;
	
}

const selectFilterType = () => {
    var optionSelected = $(this).find("option:selected");
    var valueSelected  = optionSelected.val();
    var field = $('.add-fields > main input, .edit-fields > main input').eq($(this).index()).val();
    
    sessionStorage.setItem('currentField', field);
    openModal(valueSelected);
}
const validSmartField = (type, data) => {
	if(type === 'dataset'){
		var stateValid;
		switch(data.service){
			case 'edit':
				if((data.pms.dataConstructor.priority && data.pms.dataConstructor.group) || data.pms.dataConstructor.single){
					stateValid = "editDataReady";
				}
				else{
					stateValid = "editDataError";
				}
			break;
			case 'add':
				if((data.pms.dataConstructor.priority && data.pms.dataConstructor.group) || data.pms.dataConstructor.single){
					stateValid = "addDataReady";
				}
				else{
					stateValid = "addDataError";
				}
			break;
		}

		return stateValid;
		
	}
	
	if(type === 'photogallery'){
		var stateMetaValid;
		switch(data.service){
			case 'edit':
				if(data.pms.format.length > 0 && (data.pms.galleryCount === 4 || data.pms.galleryCount > 4)){
					stateValid = "editDataReady";
				}
				else{
					stateValid = "editDataError";
				}
			break;
			case 'add':
				if(data.pms.format.length > 0 && (data.pms.galleryCount === 4 || data.pms.galleryCount > 4)){
					stateValid = "addDataReady";
				}
				else{
					stateValid = "addDataError";
				}
			break;
		}

		return stateMetaValid;
	}
}


const DataTypeListGenerator = (field, dataType) => {
	var isParameter = null,
		parameter;
						
	if(dataType === 'smartDatasets'){
		parameter = "Smart datasets";
		isParameter = true;
	}	
	else if(dataType === 'photogallery'){
		parameter = "Photogallery";
		isParameter = true;
	}	
	else if(dataType === 'precentable'){
		parameter = "Precentable value";
		isParameter = true;
	}		
	else if(dataType === 'int'){
		parameter = "Integer value";
		isParameter = true;
	}
	else if(dataType === 'cost'){
		parameter = "Cost value";
		isParameter = true;
	}
	else if(dataType === 'selecting'){
		parameter = "The form of the answer to the question";
		isParameter = true;
	}
	else if(dataType === 'text'){
		parameter = "Default value";
		isParameter = true;
	}
	
	switch(isParameter){
		case null: return <li>{list[i].Field}</li>; break;
		case true: return <li>{list[i].Field} - <span>{parameter}</span></li>; break;
	}
		
		
		
}
const RenderAttributeFiltersList = (service, query) => {
	let isList = service === 'List' ? true : false,
		isControl = service === 'Control' ? true : false;
		
	let response;
	
	let qpm = {
		parameters: {
			"attribute": query
		}
	};
	  
	const requestOptions = {
        method: 'POST',
        body: {'svcQuery': JSON.stringify(qpm)}
	};
	  
	
		
	if(isList){
		let filters;
		
		fetch('/admin/api/dataServices/filters', requestOptions).then(response => response.json()).then(data => {
			let list = data;
			
			if(list){
				filters = list.map((myState) => DataTypeListGenerator(myState.field, myState.type));
			}
			else{
				filters = <li>Not filters</li>;
			}
		});
		
		const response = <ul>{ filters }</ul>;
	}
	if(isControl){
		let controls;
		
		fetch('/admin/api/dataServices/filters', requestOptions).then(response => response.json()).then(data => {
			const buttons = data;
			
			if(buttons){
				controls = <nav><span>Edit</span><span>Delete</span></nav>;
			}
			else{
				controls = <nav><span>Add</span><span>Delete</span></nav>;
			}
		});
		
		const response = controls;
	}
	
	return response;
}


$(document).ready(function(){
  
  $(window).on('hashchange',function(){
		let s = window.location.hash;
		
		UIRender(s);
		UXRender(s);
		HeaderRender(s);
  }).trigger('hashchange');
  
});
