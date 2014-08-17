all: \
	../website/introduction.html \
	../website/advice.html \
	../website/advice_components.html \
	../website/advice_concepts.html \
	../website/advice_concurrent.html \
	../website/advice_notes.html \
	../website/advice_testing.html \
	../website/concept.html \
	../website/function.html \
	../website/type.html \
	../website/library.html \
	../website/requirements.html \
	../website/requirements_build.html \
	../website/requirements_deployment.html \
	../website/requirements_directory_structure.html \
	../website/requirements_documentation.html \
	../website/requirements_license.html \
	../website/requirements_test.html \
	../website/simple_tools.html \
	../website/tools_build_and_test.html \
	../website/tools_bjam.html \
	../website/tools_cmake.html \
	../website/tools_documentation.html \
	../website/tools_deployment.html \
	../website/about.html \
	../website/open_content.html \

#../website/%.html : %.html %.db
#	xsltproc --nonet -o $@ website.xsl $<
#	xsltproc --nonet \
#		--stringparam collect.xref.targets "only"\
#		--stringparam targets.filename "$*".db website.xsl $<

../website/%.html : %.xml
	xsltproc --nonet -o $@ website.xsl $<

../website/advice.html : website.xsl advice.xml
../website/advice_components.html : website.xsl advice_components.xml
../website/advice_concepts.html : website.xsl advice_concepts.xml
../website/advice_concurrent.html : website.xsl advice_concurrent.xml
../website/advice_notes.html : website.xsl advice_notes.xml
../website/advice_testing.html : website.xsl advice_testing.xml
../website/type.html : website.xsl type.xml
../website/concept.html : website.xsl concept.xml
../website/function.html : website.xsl function.xml
../website/introduction.html : website.xsl introduction.xml
../website/library.html : website.xsl library.xml
../website/requirements.html : website.xsl requirements.xml
../website/requirements_build.html : website.xsl requirements_build.xml
../website/requirements_deployment.html : website.xsl requirements_deployment.xml
../website/requirements_directory_structure.html : website.xsl requirements_directory_structure.xml
../website/requirements_documentation.html : website.xsl requirements_documentation.xml
../website/requirements_license.html : website.xsl requirements_license.xml
../website/requirements_test.html : website.xsl requirements_test.xml
../website/simple_tools.html : website.xsl simple_tools.xml
../website/libraries_safe_numerics.html : website.xsl libraries_safe_numerics.xml
../website/tools_documentation.html : website.xsl tools_documentation.xml
../website/tools_build_and_test.html : website.xsl tools_build_and_test.xml
../website/tools_bjam.html : website.xsl tools_bjam.xml
../website/tools_cmake.html : website.xsl tools_cmake.xml
../website/about.html : website.xsl about.xml
../website/open_content.html : website.xsl open_content.xml
