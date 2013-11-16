<?xml version='1.0'?>
<xsl:stylesheet 
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0"
    xmlns:xi="http://www.w3.org/2001/XInclude"
    exclude-result-prefixes="xi"
>

<xsl:import href="http://docbook.sourceforge.net/release/xsl/current/html/docbook.xsl"/>

<!-- 
<xsl:param name="html.stylesheet" select="'detail_page.css'"/>
-->
<!-- suppress creation of docbook.xsl -->
<xsl:param name="docbook.css.source"></xsl:param>
<!-- prevent xslt from insert style="clear: both" -->
<xsl:param name="css.decoration" select="0"/>

<xsl:param name="admon.style"/>
<xsl:param name="admon.graphics" select="1"/>
<xsl:param name="admon.graphics.extension">.png</xsl:param>
<xsl:param name="admon.graphics.path">images/</xsl:param>

<xsl:param name="callout.graphics" select="1"/>
<xsl:param name="callout.graphics.extension">.png</xsl:param>
<xsl:param name="callout.graphics.path">images/callouts/</xsl:param>
<xsl:param name="callout.graphics.number.limit">15</xsl:param>

<xsl:param name="make.clean.html" select="1"></xsl:param>
<xsl:param name="make.valid.html" select="1"></xsl:param>

<xsl:template match="replaceable"></xsl:template>
<xsl:template name="user.header.content"></xsl:template>

<xsl:template match="link">
    <a href="{@linkend}">
        <xsl:value-of select="."/>
    </a>
</xsl:template>

<xsl:template match="*" mode="process.root">
    <xsl:variable name="doc" select="self::*"/>

    <xsl:call-template name="user.preroot"/>
    <xsl:call-template name="root.messages"/>

    <xsl:call-template name="body.attributes"/>
    <xsl:call-template name="user.header.content">
        <xsl:with-param name="node" select="$doc"/>
    </xsl:call-template>
    <xsl:apply-templates select="."/>
    <xsl:call-template name="user.footer.content">
        <xsl:with-param name="node" select="$doc"/>
    </xsl:call-template>

    <xsl:value-of select="$html.append"/>

    <!-- Generate any css files only once, not once per chunk -->
    <xsl:call-template name="generate.css.files"/>
</xsl:template>

<!-- special handling for docbook book definitions -->
<xsl:template match="programlisting">
    <pre><code>
        <xsl:value-of select="."/>
    </code></pre>
</xsl:template>

<!-- special handling for boost book definitions -->
<xsl:template match="libraryname">
    <h2 class="title">
        [Library Name]
    </h2>
</xsl:template>

<xsl:template match="libraryinfo">
    <section>
        <xsl:apply-templates  select="*"></xsl:apply-templates>
    </section>
</xsl:template>
    
<xsl:template match="libraryinfo/author">
    <p>author: <xsl:apply-templates  select="*" /></p>
</xsl:template>
<xsl:template match="libraryinfo/copyright">
    <p>copyright: <xsl:apply-templates  select="*" /></p>
</xsl:template>
<xsl:template match="libraryinfo/copyright/holder">
    <p>holder: <xsl:value-of select="." /></p>
</xsl:template>
<xsl:template match="libraryinfo/copyright/year">
    <p>copyright: <xsl:value-of  select="." /></p>
</xsl:template>
<xsl:template match="libraryinfo/legalnotice">
    <p>legal notice: <xsl:value-of  select="." /></p>
</xsl:template>
<xsl:template match="libraryinfo/librarypurpose">
    <p>library purpose: <xsl:value-of  select="." /></p>
</xsl:template>
<xsl:template match="libraryinfo/librarycategory">
    <p>library category: <xsl:value-of  select="." /></p>
</xsl:template>

<!-- just skip inclusions as we handle them explicitly ! -->
<xsl:template match="xi:include"></xsl:template>

</xsl:stylesheet>
