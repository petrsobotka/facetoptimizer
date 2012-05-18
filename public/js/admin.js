


function toggleFacetValues(facetId){
	
	var valuesElement = $('facet-'+ facetId + '-values');
	var expander = $('expander-' + facetId);
	
	if(expander.hasClassName('expanded')){
    	Effect.BlindUp(valuesElement, { afterFinish: function()
            {
    			expander.removeClassName('expanded');
    		}
     	});
    	
    } else {
    	Effect.BlindDown(valuesElement, { afterFinish: function()
            {
    			expander.addClassName('expanded');
    		}
     	});
    }
}

